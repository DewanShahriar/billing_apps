<html>
<head>
    <base href="" />
    <title> Accounts Report </title>
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
                <h2>Accounts Report</h2>
            <span>  <?php echo $account_name; ?></span>
            </td>
        </tr>
    </table>
    <table id="daily_report" width="98%" border="1">
        <tr>
            <th>Sl</th>
            <th>Client Name</th>
            <th>Remark</th>
            <th>Payment Date</th>
            <th>Amount</th>
        </tr>
        @php $i=1; $total=0; @endphp
        @foreach($data as $item)
        <tr>
            <td> {{$i++}}</td>
            <td> {{$item->name}}</td>
            <td> {{$item->payment_method_account}}</td>
            <td> {{ date("d-m-Y", strtotime($item->payment_date))}}</td>
            <td> {{$item->amount}}</td>
        </tr>
         @php
            $total +=$item->amount
         @endphp
        @endforeach
        <tr>
            <td colspan="3"></td>
            <td><b>Total</b></td>
            <td><b>{{ $total}}.00</b></td>
        </tr>
    </table>

</body>
</html>