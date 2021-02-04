@include('meta')
@include('header')  

<!-- Start Slider -->
<div class="row" style="width:100%;height:100%;text-align: -webkit-center;margin: 0 0px;">
    <div class="search" style="width: 100%;place-content: center;">
        <h1 class="title-homepage" style="font-size: 45px;margin-top: 141px;"><strong>Track Your Warranty Request</strong></h1>

        <form class="searchform" method="post" action="{{ URL::to('/trackingresult')}}" style="margin: 0 30px;width: 50%;place-content: center;">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="text" placeholder="Enter a valid tracking number here..." name="search">
            <button class="hvr-hover" type="submit" style="background-color: #3C8DBC;"><i class="fa fa-search"></i></button>
        </form>

        <div class="add-pr" style="margin: 0 30px;">
            <a class="btn hvr-hover button-create" style="background-color: #3C8DBC;" href="{{ URL::to('/createForm')}}">Create a Warranty Request</a>
        </div>

        @if(!empty($result['route']))
            @if($result['route'] == 'trackingresult')
                <!-- Start of Search Result  -->
                <div class="wishlist-box-main" style="margin: 0 30px; width: 50%;place-content: center;">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-main table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #3C8DBC;">Reference Number</th>
                                            <th style="background-color: #3C8DBC;">Status</th>
                                            <th style="background-color: #3C8DBC;">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background-color:white;">
                                        @if(count($result['search']) > 0)
                                            @foreach($result['search'] as $search)
                                            <tr>
                                                <td class="name-pr">
                                                    <a href="#">
                                                        {{$search->return_reference_no}}
                                                    </a>
                                                </td>
                                                <td class="quantity-box">
                                                    <p>{{ $search->warranty_status }}</p>
                                                </td>
                                                <td class="quantity-box">
                                                    <p>{{$search->date}}</p>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="quantity-box" colspan="3">
                                                    <p align="center">No result found!</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Search Result  -->
            @endif
        @endif
    </div>
</div>
<!-- End Slider -->
@include('footer')  
@include('script')  
