@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('retrive') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <form action="{{ route('dec.home.post') }}" method="post" enctype="multipart/form-data" class="my-4">
                            @csrf

                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="userFile" name="userFile">
                                    <label class="custom-file-label" for="userFile">Choose a file</label>
                                </div>
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <div class="custom-file">--}}
{{--                                    <input type="file" class="custom-file-input" id="userFile" name="userIvFile">--}}
{{--                                    <label class="custom-file-label" for="userFile">Choose a IV file</label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="form-group">
                                <label for="enc_key">Encryption Key</label>
                                <input
                                    id="enc_key"
                                    name="userKey"
                                    type="text"
                                    class="form-control"
                                    placeholder="Key"
                                    :class="{ 'is-invalid': submitted && $v.form.last_name.$error }"
                                />
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>

                            @if (session()->has('message_decr'))
                                <div class="alert alert-success mt-3">
                                    {{ session('message_decr') }}
                                </div>
                                <div>
                                    <a href="{!! route('download', session('decrypted_file_name')) !!}">Download Decrypted file</a>
                                </div>
                            @endif

                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
