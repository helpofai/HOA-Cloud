<?php

namespace App\Modules\Security\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\File\Models\File;
use App\Modules\Security\Services\BotDetectionService;
use App\Modules\Security\Services\GhostTokenService;
use Illuminate\Support\Facades\Session;

class GhostHopController extends Controller
{
    public function __construct(
        protected BotDetectionService $botDetector,
        protected GhostTokenService $tokenService
    ) {}

    /**
     * Layer 1: The Entry Gate (Direct UUID)
     */
    public function entry(Request $request, string $uuid)
    {
        $file = File::where('uuid', $uuid)->firstOrFail();

        if ($file->is_killed) {
            abort(403, 'This content is no longer available.');
        }

        return $this->proceedToLayer2($request, $file);
    }

    /**
     * Layer 1: The Entry Gate (Share Slug)
     */
    public function entryByShare(Request $request, string $slug)
    {
        $share = \App\Modules\File\Models\Share::where('slug', $slug)->firstOrFail();

        if (!$share->is_active) {
            abort(403, 'This specific sharing link has been revoked.');
        }

        if ($share->file->is_killed) {
            abort(403, 'The underlying content has been restricted.');
        }

        // Increment hits
        $share->increment('hits');
        
        // Save share ID to session for reporting purposes later if needed
        Session::put('gh_share_id', $share->id);

        return $this->proceedToLayer2($request, $share->file);
    }

    protected function proceedToLayer2(Request $request, File $file)
    {
        if ($this->botDetector->isBot($request)) {
            // Serve a fake 404 to bots
            abort(404);
        }

        // Set a secure, HTTP-only session cookie to mark as "Entry Passed"
        Session::put('gh_layer_1_passed', true);
        Session::put('gh_target_file', $file->uuid);

        // Redirect to Layer 2 (Verification Hop)
        return redirect()->route('ghost-hop.verify', ['hash' => bin2hex(random_bytes(16))]);
    }

    /**
     * Layer 2: Verification Page
     */
    public function verify(Request $request, string $hash)
    {
        if (!Session::has('gh_layer_1_passed')) {
            abort(403, 'Unauthorized access attempt detected.');
        }

        return view('app.Modules.Security.Views.verify', [
            'hash' => $hash,
            'file' => File::where('uuid', Session::get('gh_target_file'))->first()
        ]);
    }

    /**
     * Layer 2.5: Process Verification and move to Layer 3
     */
    public function processVerification(Request $request)
    {
        if (!Session::has('gh_layer_1_passed')) {
            abort(403);
        }

        $fileUuid = Session::get('gh_target_file');
        
        // Generate an ACCESS TOKEN for Layer 3 (Player Page)
        $token = $this->tokenService->generate($fileUuid, $request->ip(), $request->userAgent());

        // We can burn the Layer 1 session marker now
        Session::forget('gh_layer_1_passed');
        Session::forget('gh_target_file');

        return redirect()->route('ghost-hop.watch', ['accessToken' => $token]);
    }

    public function reportForm(Request $request)
    {
        $fileUuid = Session::get('gh_target_file');
        if (!$fileUuid) {
            return redirect('/');
        }

        return view('app.Modules.Security.Views.report', [
            'file' => File::where('uuid', $fileUuid)->firstOrFail()
        ]);
    }

    public function submitReport(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:100',
            'details' => 'nullable|string|max:1000'
        ]);

        $fileUuid = Session::get('gh_target_file');
        $shareId = Session::get('gh_share_id');

        \App\Modules\Security\Models\AbuseReport::create([
            'file_id' => $fileUuid ? File::where('uuid', $fileUuid)->first()?->id : null,
            'share_id' => $shareId,
            'reported_url' => url()->previous(),
            'reporter_ip' => $request->ip(),
            'reason' => $request->reason,
            'details' => $request->details,
            'status' => 'pending'
        ]);

        return redirect()->route('ghost-hop.verify', ['hash' => bin2hex(random_bytes(16))])
            ->with('message', 'Report submitted successfully. Thank you for your feedback.');
    }
}
