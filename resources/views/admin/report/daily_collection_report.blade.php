<html>
<head>
    <base href="" />
    <title> Daily Collection Report </title>
    <style>
         body {
            font-family: 'bangla', sans-serif !important;
            font-size: 14px;
        }
        

        @page {
            header: page-header;
            footer: page-footer;
            margin: 20px 0px;
            padding: 0px;

        }

        @media print {
            body {
                font-family: 'bangla', sans-serif !important;
            }
            * {
                -webkit-print-color-adjust: exact;
            }
            
        }
          #daily_report{
            border-collapse: collapse;
            margin-left: 15px;
          }
          #daily_report tr,td{
            padding-left: 5px;
          }
      
    </style>
</head>

<body>
    <table width="95%" style="text-align: center; padding-bottom: 30px">
        <tr>
            <td>
                <h2>Daily Collection Report</h2>
            <span> <?php echo date("d-m-Y")?> </span>
            </td>
        </tr>
    </table>
    <table id="daily_report" width="98%" border="1">
        <tr>
            <th>Sl</th>
            <th>District Name</th>
            <th>Upazila Name</th>
            <th>Client Name</th>
            <th>Descripton</th>
            <th>Amount</th>
        </tr>
        @php $i=1; $total=0; @endphp
        @foreach($data as $item)
        <tr>
            <td> {{$i++}}</td>
            <td> {{$item->district_name}}</td>
            <td> {{$item->upazilla_name}}</td>
            <td> {{$item->name}}</td>
            <td> {{$item->remark}}</td>
            <td> {{$item->amount}}</td>
        </tr>
         @php
            $total +=$item->amount
         @endphp
        @endforeach
        <tr>
            <td colspan="4"></td>
            <td>Total</td>
            <td>{{ $total}}.00</td>
        </tr>
    </table>

</body>
</html>