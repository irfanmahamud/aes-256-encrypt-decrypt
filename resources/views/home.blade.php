@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('File TO encrypt') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                        <form action="{{ route('uploadFile') }}" method="post" enctype="multipart/form-data" class="my-4">
                            @csrf

                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="userFile" name="userFile">
                                    <label class="custom-file-label" for="userFile">Choose a file</label>
                                </div>
                            </div>
                          <div class="form-group">
                              <label for="enc_key">Encryption Key</label>
                              <input
                                  id="enc_key"
                                  name="userKey"
                                  type="text"
                                  class="form-control"
                                  placeholder="Type Key"
                                  :class="{ 'is-invalid': submitted && $v.form.last_name.$error }"
                              />
                          </div>
                           <div class="form-group">
                               <label for="iv_key">IV Length</label>
                               <input
                                   id="iv_key"
                                   name="userIv"
                                   type="text"
                                   class="form-control"
                                   placeholder="Iv Length"
                                   :class="{ 'is-invalid': submitted && $v.form.last_name.$error }"
                               />
                           </div>
                            <button type="submit" class="btn btn-primary">Upload</button>

                            @if (session()->has('message'))
                                <div class="alert alert-success mt-3">
                                    {{ session('message') }}
                                </div>

                                <div>
                                    <a href="{!! route('download', session('encrypted_file_name')) !!}">Download Encrypted File</a>
                                </div>
                                <div>
                                    <a href="{!! route('iv_download', session('encrypted_file_name')) !!}">Download Iv File</a>
                                </div>
                            @endif
                            @if(Session::get('encrypted_file_name'))



                            @endif
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
