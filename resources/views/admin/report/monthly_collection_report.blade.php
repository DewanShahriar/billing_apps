<html>
<head>
    <base href="" />
    <title> Monthly Collection Report </title>
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
                <h2>Montly Collection Report</h2>
                <span><?php 
                     $monthly_id; 
                     $month_name = date("F", mktime(0, 0, 0, $monthly_id, 10)); 
                     echo $month_name;
                     ?> 
                </span>
            </td>
        </tr>
    </table>
    <table id="daily_report" width="98%" border="1">
        <tr>
            <th>Sl</th>
            
            <th>District Name</th>
            @if($upazila_id != '')
            <th>Upazila Name</th>
             @endif
            <th>Client Name</th>
            <th>Descripton</th>
            <th>Amount</th>
        </tr>
        @php $i=1; $total=0; @endphp
        @foreach($data as $item)
        <tr>
            <td> {{$i++}}</td>
            <td> {{$item->district_name}}</td>
             @if($upazila_id != '')
             
            <td> {{$item->upazilla_name}}</td>
            @endif
            <td> {{$item->name}}</td>
            <td> {{$item->remark}}</td>
            <td> {{$item->amount}}</td>
        </tr>
         @php
            $total +=$item->amount
         @endphp
        @endforeach
        <tr>
            <?php if($upazila_id ==''){?>
            <td colspan="3"></td>
            <?php }else{ ?>
            <td colspan="4"></td>
            <?php }?>
            <td><b>Total</b></td>
            <td><b>{{ $total}}.00</b></td>
        </tr>
    </table>

</body>
</html>