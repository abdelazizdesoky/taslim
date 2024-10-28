
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
            background: #f44336; /* لون خلفية زر الإلغاء */
            color: white;
            border: none;
            border-radius: 3px;
            padding: 0 5px;
            margin-left: 10px;
            cursor: pointer;
        }
        .remove-serial-btn:hover {
            background: #d32f2f; /* لون خلفية زر الإلغاء عند التحويم */
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
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الاذن</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إضافة سيريال</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
@include('Dashboard.messages_alert')
<!-- row -->
<div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
    <div class="card">
        <div class="card-body">
            رقم الاذن - {{$Invoices->code}}
            <div class="main-content-label mg-b-5"></div>
            <p class="mg-b-20">
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
                
                 <i class="mdi mdi-truck-fast text-gray"></i> {{ $Invoices->customer->name??$Invoices->supplier->name}}
            </p>
            
            <div class="container mt-4">
          

                <div id="serialsList" class="mt-3"></div>

                <p class="mt-2">عدد السيريالات: <span id="serialCount">0</span></p>
                <!--------------------------------------------------------------------------->
                <div class="form-group">
                    <label for="serialInput">أدخل السيريال أو استخدم الكاميرا:</label>
                    <input type="text" class="form-control" id="serialInput" placeholder="أدخل السيريال هنا" autofocus>
                    <button type="button" class="btn btn-primary mt-2" id="startScanner">استخدام الكاميرا</button>
                </div>
                
                <div id="interactive" class="viewport" style="position: relative; width: 100%; height: 300px; display: none;">
                    <video autoplay="true" preload="auto" src="" muted="true" playsinline="true"></video>
                </div>
                
         <!--------------------------------------------------------------------------->
                <form class="form-horizontal" action="{{route('employee.invoices.store')}}" method="post" autocomplete="off">
                    @csrf
                    <input type="hidden" name="id" value="{{$Invoices->id}}">
                    <input type="hidden" name="serials" id="serialsHiddenInput">
                    <div class="card-footer text-left">
                        <button type="submit" class="btn btn-success waves-effect waves-light">تأكيد تسليم</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection

@section('js')

<script src="{{URL::asset('dashboard/js/quagga.min.js')}}"></script>


<script>
    
    document.addEventListener('DOMContentLoaded', function() {
    const serialInput = document.getElementById('serialInput');
    const serialsList = document.getElementById('serialsList');
    const serialCount = document.getElementById('serialCount');
    const serialsHiddenInput = document.getElementById('serialsHiddenInput');
    const startScannerBtn = document.getElementById('startScanner');
    const interactive = document.getElementById('interactive');

    // تحديث عدد السيريالات
    function updateSerialCount() {
        const serials = serialsList.querySelectorAll('.serial-item').length;
        serialCount.textContent = serials;
    }

    // تحديث الحقل المخفي
    function updateHiddenInput() {
        const serials = Array.from(serialsList.querySelectorAll('.serial-item')).map(item => item.querySelector('span').textContent);
        serialsHiddenInput.value = serials.join('\n');
    }

    // إنشاء عنصر جديد للسيريال
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

    // التحقق من وجود السيريال
    function isSerialDuplicate(serial) {
        const existingSerials = Array.from(serialsList.querySelectorAll('.serial-item')).map(item => item.querySelector('span').textContent);
        return existingSerials.includes(serial);
    }

    // عند إدخال السيريال يدويًا
    serialInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const serial = serialInput.value.trim();
            if (serial && !isSerialDuplicate(serial)) {
                createSerialItem(serial);
                serialInput.value = ''; // مسح حقل الإدخال بعد الإضافة
            } else if (isSerialDuplicate(serial)) {
                alert('هذا السيريال مكرر على مستوى الفاتورة!');
            }
        }
    });

    // عند الضغط على زر بدء الكاميرا
    startScannerBtn.addEventListener('click', function() {
        interactive.style.display = 'block'; // عرض الفيديو

        Quagga.init({
            inputStream: {
                type: "LiveStream",
                target: interactive, // الفيديو الذي سيستخدم لعرض الكاميرا
                constraints: {
                    width: 640,
                    height: 480,
                    facingMode: "environment" // استخدام الكاميرا الخلفية
                }
            },
            decoder: {
                readers: ["code_128_reader"] // نوع الباركود المدعوم
            }
        }, function(err) {
            if (err) {
                console.error(err);
                return;
            }
            Quagga.start();
        });

        // عندما يتم اكتشاف السيريال بواسطة الكاميرا
        Quagga.onDetected(function(result) {
            const serial = result.codeResult.code;
            if (serial && !isSerialDuplicate(serial)) {
                createSerialItem(serial);
                Quagga.stop();
                interactive.style.display = 'none'; // إخفاء الفيديو
            } else if (isSerialDuplicate(serial)) {
                alert('هذا السيريال مكرر على مستوى الفاتورة!');
                Quagga.stop();
                interactive.style.display = 'none'; // إخفاء الفيديو
            }
        });
    });
});


</script>


@endsection
