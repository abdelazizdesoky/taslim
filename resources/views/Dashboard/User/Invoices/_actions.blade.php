<div class="dropdown">
    <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-outline-primary btn-sm" data-toggle="dropdown"
        type="button"><i class="fas fa-caret-down mr-2"> </i> <i  class="si si-layers"> </i> </button>
    <div class="dropdown-menu tx-13">
        <a class="dropdown-item" href="{{ route('user.invoices.show', $Invoice->id) }}"><i
                style="color: #0ba360"class="fas fa-eye"></i>&nbsp;&nbsp; عرض الاذن </a>
        <a class="dropdown-item" href="{{ route('user.invoices.edit', $Invoice->id) }}"><i style="color: #0ba360"
                class="fas fa-edit"></i>&nbsp;&nbsp;تعديل الاذن </a>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#cancel{{ $Invoice->id }}"><i
                class="fas fa-times-circle"></i>&nbsp;&nbsp; الغاء الاذن</a>
     
    </div>
</div>

@include('Dashboard.User.Invoices.cancel')

