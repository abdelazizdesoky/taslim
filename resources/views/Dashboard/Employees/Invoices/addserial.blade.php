@extends('Dashboard.layouts.master')

@section('css')
<style>
    .serial-item {
        display: flex;
        align-items: center;
        padding: 5px;
        margin-bottom: 5px;
        border: 1px solid #ddd;
        border-radius: 3px;
        background-color: #f0f8ff;
    }
    .remove-serial-btn {
        background: #f44336;
        color: white;
        border: none;
        border-radius: 3px;
        padding: 0 5px;
        margin-left: 10px;
        cursor: pointer;
    }
    .remove-serial-btn:hover {
        background: #d32f2f;
    }
    #interactive video {
        width: 100%;
        height: auto;
    }
</style>
@endsection

@section('title')
إضافة سيريال
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الإذن</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">
                <a href="{{ route('employee.invoices.show', $Invoices->id) }}">/ عرض المنتجات</a> / إضافة سيريال
            </span>
        </div>
    </div>
</div>
@endsection

@section('content')
@include('Dashboard.messages_alert')
<div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
    <div class="card">
        <div class="card-body">
            <p>رقم الإذن: {{ $Invoices->code }}
          -  
                @switch($Invoices->invoice_type)
                    @case(1)
                    استلام
                    @break
                    @case(2)
                    تسليم
                    @break
                    @default
                    مرتجعات
                @endswitch
           - {{ $Invoices->customer->name ?? $Invoices->supplier->name }}</p>
            <P>عدد السيريالات المطلوبة :{{$totalQuantity}}  </P>
            <div id="serialsList" class="mt-3"></div>
            <p>عدد السيريالات: <span id="serialCount">0</span></p>
            
            <div class="form-group">
                
                <input type="text" class="form-control" id="serialInput" placeholder="أدخل السيريال هنا" autofocus>
                {{-- <button type="button" class="btn btn-primary mt-2" id="startScanner">استخدام الكاميرا</button> --}}
            </div>
            
            <div id="interactive" class="viewport" style="position: relative; width: 100%; height: 300px; display: none;">
                <video autoplay="true" muted="true" playsinline="true"></video>
            </div>
            
            <form class="form-horizontal" action="{{ route('employee.invoices.store') }}" method="post" autocomplete="off">
                @csrf
                <input type="hidden" name="id" value="{{ $Invoices->id }}">
                <input type="hidden" name="serials" id="serialsHiddenInput">
                <div class="card-footer text-left">
                    <button type="submit" class="btn btn-success waves-effect waves-light">تأكيد الإذن</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('js')
<script src="{{ URL::asset('dashboard/js/quagga.min.js') }}"></script>
<script>
    
document.addEventListener('DOMContentLoaded', function() {
    const serialInput = document.getElementById('serialInput');
    const serialsList = document.getElementById('serialsList');
    const serialCount = document.getElementById('serialCount');
    const serialsHiddenInput = document.getElementById('serialsHiddenInput');
    const startScannerBtn = document.getElementById('startScanner');
    const interactive = document.getElementById('interactive');

    function updateSerialCount() {
        const serials = serialsList.querySelectorAll('.serial-item').length;
        serialCount.textContent = serials;
    }

    function updateHiddenInput() {
        const serials = Array.from(serialsList.querySelectorAll('.serial-item')).map(item => item.querySelector('span').textContent);
        serialsHiddenInput.value = serials.join('\n');
    }

    function createSerialItem(serial) {
        const serialItem = document.createElement('div');
        serialItem.className = 'serial-item';
        const serialText = document.createElement('span');
        serialText.textContent = serial;
        serialItem.appendChild(serialText);
        const removeButton = document.createElement('button');
        removeButton.textContent = '×';
        removeButton.className = 'remove-serial-btn';
        removeButton.addEventListener('click', function() {
            serialsList.removeChild(serialItem);
            updateSerialCount();
            updateHiddenInput();
        });
        serialItem.appendChild(removeButton);
        serialsList.appendChild(serialItem);
        updateSerialCount();
        updateHiddenInput();
    }

    function isSerialDuplicate(serial) {
        const existingSerials = Array.from(serialsList.querySelectorAll('.serial-item')).map(item => item.querySelector('span').textContent);
        return existingSerials.includes(serial);
    }

    serialInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const serial = serialInput.value.trim();
            if (serial && !isSerialDuplicate(serial)) {
                createSerialItem(serial);
                serialInput.value = '';
            } else {
                alert('هذا السيريال مكرر على مستوى الفاتورة!');
            }
        }
    });

    startScannerBtn.addEventListener('click', function() {
        interactive.style.display = 'block';
        Quagga.init({
            inputStream: {
                type: "LiveStream",
                target: interactive,
                constraints: { width: 640, height: 480, facingMode: "environment" }
            },
            decoder: { readers: ["code_128_reader"] }
        }, function(err) {
            if (err) { console.error(err); return; }
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            const serial = result.codeResult.code;
            if (serial && !isSerialDuplicate(serial)) {
                createSerialItem(serial);
                Quagga.stop();
                interactive.style.display = 'none';
            } else {
                alert('هذا السيريال مكرر على مستوى الفاتورة!');
                Quagga.stop();
                interactive.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
