@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="tf-section-1 mb-30">
            <div class="flex gap20 flex-wrap-mobile">

                <div class="w-half">

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center gap14">
                            <div class="image ic-bg"><i class="icon-layers"></i></div>
                            <div>
                                <div class="body-text mb-2">Total Categories</div>
                                <h4>{{ $totalBlogs ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center gap14">
                            <div class="image ic-bg"><i class="icon-grid"></i></div>
                            <div>
                                <div class="body-text mb-2">Total Blogs</div>
                                <h4>{{ $totalCategories ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection