@extends('layouts.master')

@section('content')
@include('includes.message-block')
<div class="container">
    <section class="row justify-content-center">
        <div class="col-md-8">
            <br />
            <header>
                <h4>Store a new file!</h4>
            </header>
            <form class="form-inline" action="{{route('file.upload')}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="custom-file col">
                    <input type="file" class="custom-file-input {{ $errors->any() ? 'is-invalid' : '' }}" name="file"
                        id="validatedCustomFile" required>
                    <label class="
                        custom-file-label" for="validatedCustomFile">Choose file...
                        (Max 2 MB)</label>
                    @if($errors->any())
                    <div class="invalid-feedback">
                        {{ $errors->first() }}
                    </div>
                    @endif
                </div>
                <button type="submit" class="btn btn-dark mx-4">Store</button>
            </form>
        </div>
    </section>
    <br />
    <hr />
    <header>
        <h4>Your Files Cabinet</h4>
    </header>
    <section class="row">
        @foreach ($files as $file)
        <div class="col-sm-6 col-md-4">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <form action="{{ route('file.preview', ['file_id' => $file->id]) }}" target="_blanc" method="post">
                            {{ csrf_field() }}
                            <button type="submit" class="stream-button">{{ $file->file_name }}</button>
                        </form>
                    <form action="{{ route('file.download', ['file_id' => $file->id]) }}" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success btn-sm float-left">Download</button>
                    </form>
                    <form action="{{ route('file.delete', ['file_id' => $file->id]) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger btn-sm float-right">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </section>
</div>
@endsection

@section('js')
<script>
    $('#validatedCustomFile').on('change', function () {
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })
</script>
@endsection