<div>
    <div class="table-responsive">
        <table id="datatable"
            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead class="thead-light">
                <tr>
                    <th>{{ translate('messages.sl') }}</th>
                    <th>{{translate('messages.received_at')}}</th>
                    <th>{{translate('messages.balance_before_transaction')}}</th>
                    <th>{{translate('messages.amount')}}</th>
                    <th>{{translate('messages.reference')}}</th>
                    <th class="text-center">{{translate('messages.action')}}</th>
                </tr>
            </thead>
            <tbody>
            @php($account_transaction = \App\Models\AccountTransaction::where('from_type', 'restaurant')->where('from_id', $restaurant->vendor->id)->paginate(25))
            @foreach($account_transaction as $k=>$at)
                <tr>
                    <td scope="row">{{$k+$account_transaction->firstItem()}}</td>

                    <td>{{ Carbon\Carbon::parse($at->created_at)->locale(app()->getLocale())->translatedFormat('d M Y ' . config('timeformat')) }}</td>
                    <td>{{\App\CentralLogics\Helpers::format_currency($at['current_balance'])}}</td>
                    <td>{{\App\CentralLogics\Helpers::format_currency($at['amount'])}}</td>
                    <td>{{$at['ref']}}</td>
                    <td>
                        <div class="btn--container justify-content-center">
                            <a href="{{route('admin.account-transaction.show',[$at['id']])}}"
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
    {{$account_transaction->links()}}
        </div>
    </div>
</div>
