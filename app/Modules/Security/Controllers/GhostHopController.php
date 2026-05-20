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
     * Layer 1: The Entry Gate
     */
    public function entry(Request $request, string $uuid)
    {
        $file = File::where('uuid', $uuid)->firstOrFail();

        if ($this->botDetector->isBot($request)) {
            // Serve a fake 404 to bots
            abort(404);
        }

        // Set a secure, HTTP-only session cookie to mark as "Entry Passed"
        Session::put('gh_layer_1_passed', true);
        Session::put('gh_target_file', $uuid);

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
}
