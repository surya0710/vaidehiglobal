@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">

        <div class="flex items-center flex-wrap justify-between gap20 mb-57">
            <h3>Add HSN Code</h3>
        </div>

        <div class="wg-box">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.hsn.add') }}" method="POST" class="form-new-product">
                @csrf

                <div class="form-group" style="margin-bottom: 15px;">
                    <fieldset class="name">
                        <label class="body-title">HSN Category <span class="tf-color-1">*</span></label>
                        <input type="text" name="hsn_category" value="{{ old('hsn_category') }}" required>
                    </fieldset>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <fieldset class="hsn_code">
                        <label class="body-title">HSN Code <span class="tf-color-1">*</span></label>
                        <input type="text" name="hsn_code" value="{{ old('hsn_code') }}" required>
                    </fieldset>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <fieldset class="hsn_description">
                        <label class="body-title">HSN Description <span class="tf-color-1">*</span></label>
                        <input type="text" name="hsn_description" value="{{ old('hsn_description') }}" required>
                    </fieldset>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <fieldset class="chapter">
                        <label class="body-title">Chapter <span class="tf-color-1">*</span></label>
                        <input type="number" name="chapter" value="{{ old('chapter') }}" required>
                    </fieldset>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <fieldset class="url">
                        <label class="body-title">URL</label>
                        <input type="text" name="url" value="{{ old('url') }}">
                    </fieldset>
                </div>

                <div class="bot">
                    <button class="tf-button w208" type="submit">
                        <i class="icon-save"></i> Save HSN
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection