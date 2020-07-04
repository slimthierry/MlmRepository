@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Member</div>

                <div class="card-body">
                <form action="{{ route('member.store')}}" method="POST">
                    <div class="form-group row">
                        <label for="member" class="col-md-4 col-form-label text-md-right"></label>
                        <div class="col-md-6">
                            <input id="member" type="text" class="form-control" @error>
                            @error('member')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div form-group row>
                        <label for="parent" class="col-md-4 col-form-label text-md-right">
                            {{__('Parent Member')}}
                        </label>
                        <div class="col-md-6">
                            <select name="" id="parent" class="form-control" @error('parent') >
                                <option value="none">No any parents</option>
                                @foreach ($members as $member)
                                    <option value="{{$member->id}}">
                                        {{ $member->id}}
                                    </option>
                                @endforeach
                            </select>
                                @error('parent')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{$message}}</strong>
                                    </span>
                                @enderror
                            @endisset

                            @enderror></select>
                        </div>
                    </div>

                  </form>


                    <ul id="parrainer">

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
