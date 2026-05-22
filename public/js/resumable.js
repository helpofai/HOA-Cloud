/*
* MIT Licensed
* https://www.twentythree.com
* https://github.com/23/resumable.js
* Steffen Fagerström Christensen, steffen@twentythree.com
*/

(function(){
"use strict";

  var Resumable = function(opts){
    if ( !(this instanceof Resumable) ) {
      return new Resumable(opts);
    }
    this.version = 1.0;
    this.support = (
                   (typeof(File)!=='undefined')
                   &&
                   (typeof(Blob)!=='undefined')
                   &&
                   (typeof(FileList)!=='undefined')
                   &&
                   (!!Blob.prototype.webkitSlice||!!Blob.prototype.mozSlice||!!Blob.prototype.slice||false)
                   );
    if(!this.support) return(false);


    // PROPERTIES
    var $ = this;
    $.files = [];
    $.defaults = {
      chunkSize:1*1024*1024,
      forceChunkSize:false,
      simultaneousUploads:3,
      fileParameterName:'file',
      chunkNumberParameterName: 'resumableChunkNumber',
      chunkSizeParameterName: 'resumableChunkSize',
      currentChunkSizeParameterName: 'resumableCurrentChunkSize',
      totalSizeParameterName: 'resumableTotalSize',
      typeParameterName: 'resumableType',
      identifierParameterName: 'resumableIdentifier',
      fileNameParameterName: 'resumableFilename',
      relativePathParameterName: 'resumableRelativePath',
      totalChunksParameterName: 'resumableTotalChunks',
      dragOverClass: 'dragover',
      throttleProgressCallbacks: 0.5,
      query:{},
      headers:{},
      preprocess:null,
      preprocessFile:null,
      method:'multipart',
      uploadMethod: 'POST',
      testMethod: 'GET',
      prioritizeFirstAndLastChunk:false,
      target:'/',
      testTarget: null,
      parameterNamespace:'',
      testChunks:true,
      generateUniqueIdentifier:null,
      getTarget:null,
      maxChunkRetries:100,
      chunkRetryInterval:undefined,
      permanentErrors:[400, 401, 403, 404, 409, 415, 500, 501],
      maxFiles:undefined,
      withCredentials:false,
      xhrTimeout:0,
      clearInput:true,
      chunkFormat:'blob',
      setChunkTypeFromFile:false,
      maxFilesErrorCallback:function (files, errorCount) {
        var maxFiles = $.getOpt('maxFiles');
        alert('Please upload no more than ' + maxFiles + ' file' + (maxFiles === 1 ? '' : 's') + ' at a time.');
      },
      minFileSize:1,
      minFileSizeErrorCallback:function(file, errorCount) {
        alert(file.fileName||file.name +' is too small, please upload files larger than ' + $h.formatSize($.getOpt('minFileSize')) + '.');
      },
      maxFileSize:undefined,
      maxFileSizeErrorCallback:function(file, errorCount) {
        alert(file.fileName||file.name +' is too large, please upload files less than ' + $h.formatSize($.getOpt('maxFileSize')) + '.');
      },
      fileType: [],
      fileTypeErrorCallback: function(file, errorCount) {
        alert(file.fileName||file.name +' has type not allowed, please upload files of type ' + $.getOpt('fileType') + '.');
      }
    };
    $.opts = opts||{};
    $.getOpt = function(o) {
      var $opt = this;
      if(o instanceof Array) {
        var options = {};
        $h.each(o, function(option){
          options[option] = $opt.getOpt(option);
        });
        return options;
      }
      if ($opt instanceof ResumableChunk) {
        if (typeof $opt.opts[o] !== 'undefined') { return $opt.opts[o]; }
        else { $opt = $opt.fileObj; }
      }
      if ($opt instanceof ResumableFile) {
        if (typeof $opt.opts[o] !== 'undefined') { return $opt.opts[o]; }
        else { $opt = $opt.resumableObj; }
      }
      if ($opt instanceof Resumable) {
        if (typeof $opt.opts[o] !== 'undefined') { return $opt.opts[o]; }
        else { return $opt.defaults[o]; }
      }
    };
    $.indexOf = function(array, obj) {
    	if (array.indexOf) { return array.indexOf(obj); }     
    	for (var i = 0; i < array.length; i++) {
            if (array[i] === obj) { return i; }
        }
        return -1;
    }
    $.getFromUniqueIdentifier = function(uniqueIdentifier){
      var ret = false;
      $h.each($.files, function(f){
        if(f.uniqueIdentifier==uniqueIdentifier) ret = f;
      });
      return(ret);
    };
    $.isUploading = function(){
      var uploading = false;
      $h.each($.files, function(f){
        if(f.isUploading()) {
          uploading = true;
          return(false);
        }
      });
      return(uploading);
    };

    $.events = [];
    $.on = function(event,callback){
      $.events.push(event.toLowerCase(), callback);
    };
    $.fire = function(){
      var args = [];
      for (var i=0; i<arguments.length; i++) args.push(arguments[i]);
      var event = args[0].toLowerCase();
      for (var i=0; i<=$.events.length; i+=2) {
        if($.events[i]==event) $.events[i+1].apply($,args.slice(1));
        if($.events[i]=='catchall') $.events[i+1].apply(null,args);
      }
      if(event=='fileerror') $.fire('error', args[2], args[1]);
      if(event=='fileprogress') $.fire('progress');
    };


    var $h = {
      stopEvent: function(e){
        e.stopPropagation();
        e.preventDefault();
      },
      each: function(o,callback){
        if(typeof(o.length)!=='undefined') {
          for (var i=0; i<o.length; i++) {
            if(callback(o[i])===false) return;
          }
        } else {
          for (i in o) {
            if(callback(i,o[i])===false) return;
          }
        }
      },
      generateUniqueIdentifier:function(file, event){
        var custom = $.getOpt('generateUniqueIdentifier');
        if(typeof custom === 'function') {
          return custom(file, event);
        }
        var relativePath = file.webkitRelativePath||file.fileName||file.name;
        var size = file.size;
        return(size + '-' + relativePath.replace(/[^0-9a-zA-Z_-]/img, ''));
      },
      getTarget: function(type, params){
        var target = $.getOpt('target');
        if (type === 'test') {
            target = $.getOpt('testTarget') || target;
        }
        var getTarget = $.getOpt('getTarget');
        if (typeof getTarget === 'function') {
            target = getTarget(type, params);
        }
        if (params.length > 0) {
            var separator = (target.indexOf('?') < 0) ? '?' : '&';
            target += separator + params.join('&');
        }
        return target;
      },
      contains: function(array, obj) {
        return $.indexOf(array, obj) !== -1;
      },
      formatSize:function(size){
        if(size<1024) return size + ' bytes';
        if(size<1024*1024) return (size/1024).toFixed(0) + ' KB';
        if(size<1024*1024*1024) return (size/1024/1024).toFixed(1) + ' MB';
        return (size/1024/1024/1024).toFixed(1) + ' GB';
      }
    };

    var onDragOverEnter = function(e){
      $h.stopEvent(e);
      e.dataTransfer.dropEffect = 'copy';
      var domNode = e.currentTarget;
      var dragOverClass = $.getOpt('dragOverClass');
      if (domNode && !dragOverClass.includes(domNode.className)) {
        domNode.className += ' ' + dragOverClass;
      }
    };
    var onDragLeave = function(e){
      var domNode = e.currentTarget;
      var dragOverClass = $.getOpt('dragOverClass');
      if (domNode && dragOverClass) {
        domNode.className = domNode.className.replace(' ' + dragOverClass, '');
      }
    };
    var onDrop = function(e){
      $h.stopEvent(e);
      var domNode = e.currentTarget;
      var dragOverClass = $.getOpt('dragOverClass');
      if (domNode && dragOverClass) {
        domNode.className = domNode.className.replace(' ' + dragOverClass, '');
      }
      appendFilesFromFileList(e.dataTransfer.files, e);
    };

    var appendFilesFromFileList = function(fileList, event){
      var files = [];
      $h.each(fileList, function(file){
        if (typeof(file.size)!=='undefined' && file.size > 0) {
            if ( (typeof($.getOpt('minFileSize'))!=='undefined' && file.size<$.getOpt('minFileSize')) ) {
              $.getOpt('minFileSizeErrorCallback')(file);
              return;
            }
            if ( (typeof($.getOpt('maxFileSize'))!=='undefined' && file.size>$.getOpt('maxFileSize')) ) {
              $.getOpt('maxFileSizeErrorCallback')(file);
              return;
            }
            if ($.getOpt('fileType').length > 0) {
              var fileType = file.type || '';
              var fileName = file.name || '';
              var extension = fileName.split('.').pop().toLowerCase();
              var found = false;
              $h.each($.getOpt('fileType'), function(type){
                type = type.replace(/\s/g, '').toLowerCase();
                if (type.indexOf('/') !== -1) {
                  if (fileType === type) {
                    found = true;
                    return false;
                  }
                } else {
                  if (type.indexOf('.') === 0) {
                    if ('.' + extension === type) {
                      found = true;
                      return false;
                    }
                  } else {
                    if (extension === type) {
                      found = true;
                      return false;
                    }
                  }
                }
              });
              if (!found) {
                $.getOpt('fileTypeErrorCallback')(file);
                return;
              }
            }

            var uniqueIdentifier = $h.generateUniqueIdentifier(file, event);
            if(!$.getFromUniqueIdentifier(uniqueIdentifier)){
              var f = new ResumableFile($, file, uniqueIdentifier);
              $.files.push(f);
              files.push(f);
              $.fire('fileAdded', f, event);
            }
        }
      });
      if(files.length>0){
        $.fire('filesAdded', files);
      }
    };


    function ResumableFile(resumableObj, file, uniqueIdentifier){
      var $ = this;
      $.opts = {};
      $.getOpt = resumableObj.getOpt;
      $.resumableObj = resumableObj;
      $.file = file;
      $.fileName = file.fileName||file.name;
      $.size = file.size;
      $.relativePath = file.webkitRelativePath||$.fileName;
      $.uniqueIdentifier = uniqueIdentifier;
      $._pause = false;
      $.preprocessState = 0; // 0 = unprocessed, 1 = processing, 2 = finished
      var _error = false;

      var chunkEvent = function(event, message){
        switch(event){
        case 'progress':
          $.resumableObj.fire('fileProgress', $, message);
          break;
        case 'error':
          $.abort();
          _error = true;
          $.chunks = [];
          $.resumableObj.fire('fileError', $, message);
          break;
        case 'success':
          if(_error) return;
          $.resumableObj.fire('fileProgress', $, message);
          if($.isComplete()) {
            $.resumableObj.fire('fileSuccess', $, message);
          }
          break;
        case 'retry':
          $.resumableObj.fire('fileRetry', $);
          break;
        }
      };

      $.chunks = [];
      $.abort = function(){
        var abortCount = 0;
        $h.each($.chunks, function(c){
          if(c.status()=='uploading') {
            c.abort();
            abortCount++;
          }
        });
        if(abortCount>0) $.resumableObj.fire('fileProgress', $);
      };
      $.cancel = function(){
        var _chunks = $.chunks;
        $.chunks = [];
        $h.each(_chunks, function(c){
          if(c.status()=='uploading')  {
            c.abort();
            $.resumableObj.uploadNextChunk();
          }
        });
        $.resumableObj.removeFile($);
        $.resumableObj.fire('fileProgress', $);
      };
      $.retry = function(){
        $.bootstrap();
        var firedRetry = false;
        $.resumableObj.on('chunkingComplete', function(){
          if(!firedRetry) $.resumableObj.upload();
          firedRetry = true;
        });
      };
      $.bootstrap = function(){
        $.abort();
        _error = false;
        $.chunks = [];
        $._prevProgress = 0;
        var round = $.getOpt('forceChunkSize') ? Math.ceil : Math.floor;
        var maxOffset = Math.max(round($.file.size/$.getOpt('chunkSize')),1);
        for (var offset=0; offset<maxOffset; offset++) {(function(offset){
            $.chunks.push(new ResumableChunk($.resumableObj, $, offset, chunkEvent));
            $.resumableObj.fire('chunkingProgress',$,offset/maxOffset);
        })(offset)}
        window.setTimeout(function(){
            $.resumableObj.fire('chunkingComplete',$);
        },0);
      };
      $.progress = function(){
        if(_error) return(1);
        var ret = 0;
        var error = false;
        $h.each($.chunks, function(c){
          if(c.status()=='error') error = true;
          ret += c.progress(true);
        });
        ret = (error ? 1 : (ret>0.99999 ? 1 : ret));
        ret = Math.max($._prevProgress, ret);
        $._prevProgress = ret;
        return(ret);
      };
      $.isUploading = function(){
        var uploading = false;
        $h.each($.chunks, function(chunk){
          if(chunk.status()=='uploading') {
            uploading = true;
            return(false);
          }
        });
        return(uploading);
      };
      $.isComplete = function(){
        var outstanding = false;
        if ($.preprocessState === 1) {
          return(false);
        }
        $h.each($.chunks, function(chunk){
          var status = chunk.status();
          if(status=='pending' || status=='uploading' || chunk.preprocessState === 1) {
            outstanding = true;
            return(false);
          }
        });
        return(!outstanding);
      };
      $.pause = function(pause){
          if(typeof(pause)==='undefined'){
              $._pause = ($._pause ? false : true);
          }else{
              $._pause = pause;
          }
      };
      $.isPaused = function() {
        return $._pause;
      };
      $.preprocessFinished = function(){
        $.preprocessState = 2;
        $.upload();
      };
      $.upload = function () {
        var found = false;
        if ($.isPaused() === false) {
          var preprocess = $.getOpt('preprocessFile');
          if(typeof preprocess === 'function') {
            switch($.preprocessState) {
            case 0: $.preprocessState = 1; preprocess($); return(true);
            case 1: return(true);
            case 2: break;
            }
          }
          $h.each($.chunks, function (chunk) {
            if (chunk.status() == 'pending' && chunk.preprocessState !== 1) {
              chunk.send();
              found = true;
              return(false);
            }
          });
        }
        return(found);
      }
      $.markChunksCompleted = function (chunkNumber) {
        if (!$.chunks || $.chunks.length <= chunkNumber) {
            return;
        }
        for (var num = 0; num < chunkNumber; num++) {
            $.chunks[num].markComplete = true;
        }
      };

      $.resumableObj.fire('chunkingStart', $);
      $.bootstrap();
      return(this);
    }


    function ResumableChunk(resumableObj, fileObj, offset, callback){
      var $ = this;
      $.opts = {};
      $.getOpt = resumableObj.getOpt;
      $.resumableObj = resumableObj;
      $.fileObj = fileObj;
      $.fileObjSize = fileObj.size;
      $.fileObjType = fileObj.file.type;
      $.offset = offset;
      $.callback = callback;
      $.lastProgressCallback = (new Date);
      $.tested = false;
      $.retries = 0;
      $.pendingRetry = false;
      $.preprocessState = 0; 
      $.markComplete = false;

      var chunkSize = $.getOpt('chunkSize');
      $.loaded = 0;
      $.startByte = $.offset*chunkSize;
      $.endByte = Math.min($.fileObjSize, ($.offset+1)*chunkSize);
      if ($.fileObjSize-$.endByte < chunkSize && !$.getOpt('forceChunkSize')) {
        $.endByte = $.fileObjSize;
      }
      $.xhr = null;

      $.test = function(){
        $.xhr = new XMLHttpRequest();
        var testHandler = function(e){
          $.tested = true;
          var status = $.status();
          if(status=='success') {
            $.callback(status, $.message());
            $.resumableObj.uploadNextChunk();
          } else {
            $.send();
          }
        };
        $.xhr.addEventListener('load', testHandler, false);
        $.xhr.addEventListener('error', testHandler, false);
        $.xhr.addEventListener('timeout', testHandler, false);

        var params = [];
        var parameterNamespace = $.getOpt('parameterNamespace');
        var customQuery = $.getOpt('query');
        if(typeof customQuery == 'function') customQuery = customQuery($.fileObj, $);
        $h.each(customQuery, function(k,v){
          params.push([encodeURIComponent(parameterNamespace+k), encodeURIComponent(v)].join('='));
        });
        params = params.concat(
          [
            ['chunkNumberParameterName', $.offset + 1],
            ['chunkSizeParameterName', $.getOpt('chunkSize')],
            ['currentChunkSizeParameterName', $.endByte - $.startByte],
            ['totalSizeParameterName', $.fileObjSize],
            ['typeParameterName', $.fileObjType],
            ['identifierParameterName', $.fileObj.uniqueIdentifier],
            ['fileNameParameterName', $.fileObj.fileName],
            ['relativePathParameterName', $.fileObj.relativePath],
            ['totalChunksParameterName', $.fileObj.chunks.length]
          ].filter(function(pair){
            return $.getOpt(pair[0]);
          })
          .map(function(pair){
            return [
              parameterNamespace + $.getOpt(pair[0]),
              encodeURIComponent(pair[1])
            ].join('=');
          })
        );
        $.xhr.open($.getOpt('testMethod'), $h.getTarget('test', params));
        $.xhr.timeout = $.getOpt('xhrTimeout');
        $.xhr.withCredentials = $.getOpt('withCredentials');
        var customHeaders = $.getOpt('headers');
        if(typeof customHeaders === 'function') {
          customHeaders = customHeaders($.fileObj, $);
        }
        $h.each(customHeaders, function(k,v) {
          $.xhr.setRequestHeader(k, v);
        });
        $.xhr.send(null);
      };

      $.preprocessFinished = function(){
        $.preprocessState = 2;
        $.send();
      };

      $.send = function(){
        var preprocess = $.getOpt('preprocess');
        if(typeof preprocess === 'function') {
          switch($.preprocessState) {
          case 0: $.preprocessState = 1; preprocess($); return;
          case 1: return;
          case 2: break;
          }
        }
        if($.getOpt('testChunks') && !$.tested) {
          $.test();
          return;
        }

        $.xhr = new XMLHttpRequest();

        $.xhr.upload.addEventListener('progress', function(e){
          if( (new Date) - $.lastProgressCallback > $.getOpt('throttleProgressCallbacks') * 1000 ) {
            $.callback('progress');
            $.lastProgressCallback = (new Date);
          }
          $.loaded=e.loaded||0;
        }, false);
        $.loaded = 0;
        $.pendingRetry = false;
        $.callback('progress');

        var doneHandler = function(e){
          var status = $.status();
          if(status=='success'||status=='error') {
            $.callback(status, $.message());
            $.resumableObj.uploadNextChunk();
          } else {
            $.callback('retry', $.message());
            $.abort();
            $.retries++;
            var retryInterval = $.getOpt('chunkRetryInterval');
            if(retryInterval !== undefined) {
              $.pendingRetry = true;
              setTimeout($.send, retryInterval);
            } else {
              $.send();
            }
          }
        };
        $.xhr.addEventListener('load', doneHandler, false);
        $.xhr.addEventListener('error', doneHandler, false);
        $.xhr.addEventListener('timeout', doneHandler, false);

        var query = [
          ['chunkNumberParameterName', $.offset + 1],
          ['chunkSizeParameterName', $.getOpt('chunkSize')],
          ['currentChunkSizeParameterName', $.endByte - $.startByte],
          ['totalSizeParameterName', $.fileObjSize],
          ['typeParameterName', $.fileObjType],
          ['identifierParameterName', $.fileObj.uniqueIdentifier],
          ['fileNameParameterName', $.fileObj.fileName],
          ['relativePathParameterName', $.fileObj.relativePath],
          ['totalChunksParameterName', $.fileObj.chunks.length],
        ].filter(function(pair){
          return $.getOpt(pair[0]);
        })
        .reduce(function(query, pair){
          query[$.getOpt(pair[0])] = pair[1];
          return query;
        }, {});
        var customQuery = $.getOpt('query');
        if(typeof customQuery == 'function') customQuery = customQuery($.fileObj, $);
        $h.each(customQuery, function(k,v){
          query[k] = v;
        });

        var func = ($.fileObj.file.slice ? 'slice' : ($.fileObj.file.mozSlice ? 'mozSlice' : ($.fileObj.file.webkitSlice ? 'webkitSlice' : 'slice')));
        var bytes = $.fileObj.file[func]($.startByte, $.endByte, $.getOpt('setChunkTypeFromFile') ? $.fileObj.file.type : "");
        var data = null;
        var params = [];

        var parameterNamespace = $.getOpt('parameterNamespace');
        if ($.getOpt('method') === 'octet') {
            data = bytes;
            $h.each(query, function (k, v) {
                params.push([encodeURIComponent(parameterNamespace + k), encodeURIComponent(v)].join('='));
            });
        } else {
            data = new FormData();
            $h.each(query, function (k, v) {
                data.append(parameterNamespace + k, v);
                params.push([encodeURIComponent(parameterNamespace + k), encodeURIComponent(v)].join('='));
            });
            if ($.getOpt('chunkFormat') == 'blob') {
                data.append(parameterNamespace + $.getOpt('fileParameterName'), bytes, $.fileObj.fileName);
            }
        }

        var target = $h.getTarget('upload', params);
        var method = $.getOpt('uploadMethod');

        $.xhr.open(method, target);
        if ($.getOpt('method') === 'octet') {
          $.xhr.setRequestHeader('Content-Type', 'application/octet-stream');
        }
        $.xhr.timeout = $.getOpt('xhrTimeout');
        $.xhr.withCredentials = $.getOpt('withCredentials');
        var customHeaders = $.getOpt('headers');
        if(typeof customHeaders === 'function') {
          customHeaders = customHeaders($.fileObj, $);
        }

        $h.each(customHeaders, function(k,v) {
          $.xhr.setRequestHeader(k, v);
        });

        if ($.getOpt('chunkFormat') == 'blob') {
            $.xhr.send(data);
        }
      };
      $.abort = function(){
        if($.xhr) $.xhr.abort();
        $.xhr = null;
      };
      $.status = function(){
        if($.pendingRetry) {
          return('uploading');
        } else if($.markComplete) {
          return 'success';
        } else if(!$.xhr) {
          return('pending');
        } else if($.xhr.readyState<4) {
          return('uploading');
        } else {
          if($.xhr.status == 200 || $.xhr.status == 201) {
            return('success');
          } else if($h.contains($.getOpt('permanentErrors'), $.xhr.status) || $.retries >= $.getOpt('maxChunkRetries')) {
            return('error');
          } else {
            $.abort();
            return('pending');
          }
        }
      };
      $.message = function(){
        return($.xhr ? $.xhr.responseText : '');
      };
      $.progress = function(relative){
        if(typeof(relative)==='undefined') relative = false;
        var factor = (relative ? ($.endByte-$.startByte)/$.fileObjSize : 1);
        if($.pendingRetry) return(0);
        if((!$.xhr || !$.xhr.status) && !$.markComplete) factor*=.95;
        var s = $.status();
        switch(s){
        case 'success':
        case 'error':
          return(1*factor);
        case 'pending':
          return(0*factor);
        default:
          return($.loaded/($.endByte-$.startByte)*factor);
        }
      };
      return(this);
    }

    $.uploadNextChunk = function(){
      var found = false;
      if ($.getOpt('prioritizeFirstAndLastChunk')) {
        $h.each($.files, function(file){
          if(file.chunks.length && file.chunks[0].status()=='pending' && file.chunks[0].preprocessState === 0) {
            file.chunks[0].send();
            found = true;
            return(false);
          }
          if(file.chunks.length>1 && file.chunks[file.chunks.length-1].status()=='pending' && file.chunks[file.chunks.length-1].preprocessState === 0) {
            file.chunks[file.chunks.length-1].send();
            found = true;
            return(false);
          }
        });
        if(found) return(true);
      }
      $h.each($.files, function(file){
        found = file.upload();
        if(found) return(false);
      });
      if(found) return(true);
      var outstanding = false;
      $h.each($.files, function(file){
        if(!file.isComplete()) {
          outstanding = true;
          return(false);
        }
      });
      if(!outstanding) {
        $.fire('complete');
      }
      return(false);
    };

    $.assignBrowse = function(domNodes, isDirectory){
      if(typeof(domNodes.length)=='undefined') domNodes = [domNodes];
      $h.each(domNodes, function(domNode) {
        var input;
        if(domNode.tagName==='INPUT' && domNode.type==='file'){
          input = domNode;
        } else {
          input = document.createElement('input');
          input.setAttribute('type', 'file');
          input.style.display = 'none';
          domNode.addEventListener('click', function(){
            input.style.opacity = 0;
            input.style.display='block';
            input.focus();
            input.click();
            input.style.display='none';
          }, false);
          domNode.appendChild(input);
        }
        var maxFiles = $.getOpt('maxFiles');
        if (typeof(maxFiles)==='undefined'||maxFiles!=1){
          input.setAttribute('multiple', 'multiple');
        } else {
          input.removeAttribute('multiple');
        }
        if(isDirectory){
          input.setAttribute('webkitdirectory', 'webkitdirectory');
        } else {
          input.removeAttribute('webkitdirectory');
        }
        var fileTypes = $.getOpt('fileType');
        if (typeof (fileTypes) !== 'undefined' && fileTypes.length >= 1) {
          input.setAttribute('accept', fileTypes.map(function (e) {
            e = e.replace(/\s/g, '').toLowerCase();
            if(e.match(/^[^.][^/]+$/)){
              e = '.' + e;
            }
            return e;
          }).join(','));
        }
        input.addEventListener('change', function(e){
          appendFilesFromFileList(e.target.files,e);
          var clearInput = $.getOpt('clearInput');
          if (clearInput) {
            e.target.value = '';
          }
        }, false);
      });
    };
    $.assignDrop = function(domNodes){
      if(typeof(domNodes.length)=='undefined') domNodes = [domNodes];
      $h.each(domNodes, function(domNode) {
        domNode.addEventListener('dragover', onDragOverEnter, false);
        domNode.addEventListener('dragenter', onDragOverEnter, false);
        domNode.addEventListener('dragleave', onDragLeave, false);
        domNode.addEventListener('drop', onDrop, false);
      });
    };
    $.upload = function(){
      if($.isUploading()) return;
      $.fire('uploadStart');
      for (var num=1; num<=$.getOpt('simultaneousUploads'); num++) {
        $.uploadNextChunk();
      }
    };
    $.progress = function(){
      var totalDone = 0;
      var totalSize = 0;
      $h.each($.files, function(file){
        totalDone += file.progress()*file.size;
        totalSize += file.size;
      });
      return(totalSize>0 ? totalDone/totalSize : 0);
    };
    $.removeFile = function(file){
      for(var i = $.files.length - 1; i >= 0; i--) {
        if($.files[i] === file) {
          $.files.splice(i, 1);
        }
      }
    };
    $.getSize = function(){
      var totalSize = 0;
      $h.each($.files, function(file){
        totalSize += file.size;
      });
      return(totalSize);
    };

    return(this);
  };

  if (typeof module != 'undefined') {
    module.exports = Resumable;
  } else {
    window.Resumable = Resumable;
  }
})();
