<div>
    <div class="table-responsive">
        <table id="datatable"
            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table text-center">
            <thead class="thead-light">
                <tr>
                    <th>{{translate('messages.sl')}}</th>
                    <th>{{translate('withdraw_request_date')}}</th>
                    <th>{{translate('messages.amount')}}</th>
                    <th>{{translate('messages.status')}}</th>
                    <th>{{translate('messages.action')}}</th>
                </tr>
            </thead>
            <tbody>
            @php($withdraw_transaction = \App\Models\WithdrawRequest::where('vendor_id', $restaurant->vendor->id)->paginate(25))
            @foreach($withdraw_transaction as $k=>$wt)
                <tr>
                    <td scope="row">{{$k+$withdraw_transaction->firstItem()}}</td>
                    <td>{{  Carbon\Carbon::parse($wt->created_at)->locale(app()->getLocale())->translatedFormat('d M Y ' . config('timeformat'))  }}
                        {{-- {{date('Y-m-d '.config('timeformat'), strtotime($wt->created_at))}} --}}
                    </td>
                    <td>{{\App\CentralLogics\Helpers::format_currency($wt->amount)}}</td>
                    <td>
                        @if($wt->approved==0)
                            <label class="badge badge-primary">{{ translate('Pending') }}</label>
                        @elseif($wt->approved==1)
                            <label class="badge badge-success">{{ translate('Approved') }}</label>
                        @else
                            <label class="badge badge-danger">{{ translate('Denied') }}</label>
                        @endif
                    </td>
                    <td>
                        <div class="btn--container">
                            <a href="{{route('admin.restaurant.withdraw_view',[$wt['id'],$restaurant->vendor['id']])}}"
                                class="btn btn-sm btn--warning btn-outline-warning action-btn"><i class="tio-visible"></i>
                            </a>
                    </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
</div>
<div class="page-area px-4 pb-3">
    <div class="d-flex align-items-center justify-content-end">
        {{-- <div>
            1-15 of 380
        </div> --}}
        <div>
    {!!$withdraw_transaction->links()!!}
        </div>
    </div>
</div>
