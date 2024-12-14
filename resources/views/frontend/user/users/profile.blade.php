@extends('frontend.user.layouts.master')

@section('title','User Profile')

@section('main-content')

    <form class="border px-4 pt-2 pb-3" method="POST" action="{{route('user-profile-update',$profile->id)}}">
        @csrf
        <div class="form-group">
            <label for="inputTitle" class="col-form-label">Name</label>
            <input id="inputTitle" type="text" name="name" placeholder="Enter name"  value="{{$profile->name}}" class="form-control">
            @error('name')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="inputEmail" class="col-form-label">Email</label>
            <input id="inputEmail" disabled type="email" name="email" placeholder="Enter email"  value="{{$profile->email}}" class="form-control">
            @error('email')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="inputPhoto" class="col-form-label">Photo</label>
            <div class="input-group">
                <span class="input-group-btn">
                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                        <i class="fa fa-picture-o"></i> Choose
                    </a>
                </span>
                <input id="thumbnail" class="form-control" type="text" name="photo" value="{{$profile->photo}}">
            </div>
            @error('photo')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <!-- Button Wrapper for Right Alignment -->
        <div class="form-group text-right">
            <button type="submit" class="btn btn-sm">Update</button>
        </div>
    </form>

@endsection

<style>
    /* Styling for the button */
    button.btn {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
    }

    button.btn:hover {
        background-color: #0056b3;
    }

    .breadcrumbs {
        list-style: none;
    }

    .breadcrumbs li {
        float: left;
        margin-right: 10px;
    }

    .breadcrumbs li a:hover {
        text-decoration: none;
    }

    .breadcrumbs li .active {
        color: red;
    }

    .breadcrumbs li+li:before {
        content: "/\00a0";
    }

    .image {
        background: url('{{asset('backend/img/background.jpg')}}');
        height: 200px; /* Height of the background */
        background-position: center;
        background-attachment: cover;
        position: relative;
    }

    .image img {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        height: 150px; /* Bigger image */
        width: 150px; /* Bigger image */
        border-radius: 50%; /* Circle shape */
        object-fit: cover; /* Adjust the image fill */
    }

    i {
        font-size: 14px;
        padding-right: 8px;
    }

    /* Align button to the right */
    .form-group.text-right {
        text-align: right;
    }
</style>

@push('scripts')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('#lfm').filemanager('image');
    </script>
@endpush
