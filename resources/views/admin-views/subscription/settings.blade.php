@extends('layouts.admin.app')

@section('title',translate('Subscription Settings'))

@push('css_or_js')
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">{{translate('messages.Subscription Settings')}}</h1>
                <h5 class="d-flex flex-wrap justify-content-end">
                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                        <span class="mr-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                        <span class="mr-2 switch--custom-label-text off ">{{ translate('messages.Status') }}</span>
                        <input type="checkbox" id="data_status"   class="toggle-switch-input" {{$free_trial_period?($free_trial_period['status']==1?'checked':''):''}}>
                        <span class="toggle-switch-label text">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </h5>
            </div>
        </div>
    </div>
    <!-- End Page Header -->
    <div class="row gx-2 gx-lg-3">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{route('admin.subscription.settings_update')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                <label class="input-label text--black" for="exampleFormControlInput1"> {{translate('messages.Free_trial_period')}} ({{translate('messages.days')}})</label>
                                    <input type="number" value="{{ isset($free_trial_period['data']) ? $free_trial_period['data'] : ''}}" name="free_trial_period" class="form-control h--45px" placeholder="{{ translate('Ex: 90 Days ') }}" min="1" step="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>



@endsection
@push('script_2')
<script type="text/javascript">



    $(document).ready(function () {
                $('body').on('change','#data_status', function(){
                if(this.checked){
                var status = 1;
                }else{
                var status = 0;
                }
            url= '{{ url('admin/subscription/settings/update') }}/'+status;
            $.ajax({
                url: url,
                method: 'get',
                success: function(result) {
                    toastr.success('{{ translate('messages.status updated!') }}', {
                    CloseButton: true,
                    ProgressBar: true
                    });
                }
            });

            });
        });
</script>
@endpush
