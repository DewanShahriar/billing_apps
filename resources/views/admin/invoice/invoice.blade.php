<html>
<head>
    <base href="" />
    <title>Invoice</title>
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
      
      
    </style>
</head>

<body>

    <table border="0" width="95%" align="center" style="border-collapse:collapse; margin:2px auto; padding-top: 50px">
            <tr>
                <td style="width:1.5in; text-align:center;"><img src="{{ asset('/')}}assets/images/innovation_logo.PNG" height="40px" width="150px" /></td>

                <td style="text-align:center;">
                    <font style="font-size:25px; font-weight:bold; color:blue;"></font>

                    <br />

                    <font style="font-size:16px; font-weight:bold;">
                        <!-- <br> -->
                            (Office Copy)
                    </font>

                </td>

                <td style="width:1.2in; text-align:left;">

                   
                    <!-- <img src="" height="80px" width="80px" style="position:relative;right:10px;" /> -->
                    

                </td>

            </tr>
        </table> 

        <table border="0" width="95%" align="center" style="border-collapse:collapse; margin:10px auto;">
            
  
            <tr>
                <td style="padding-left: 50px;width: 150px;"><b>Client name </b></td>
                <td>
                    <font>&nbsp;:&nbsp;{{$client_details->name}}</font>
                </td>

               <td align='right'><b>Invoice no </b> </td>
                <td>
                    <font>&nbsp;:&nbsp;{{$invoice_data->invoice_id}}</font>
                </td>

            </tr>
            <tr>
                <td style="padding-left: 50px"><b>Address</b> </td>
                <td>
                    <font>&nbsp;:&nbsp;{{$client_details->address}}</font>
                </td>
                <td align='right'><b>Generated date </b> </td>
                <td>
                    <font>&nbsp;:@php
                                $date = $invoice_data->created_at;
                                $credate = substr($date,0,10);
                                @endphp
                                {{$credate}}</font>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 50px"><b>Mobile no</b></td>
                <td>
                    <font>&nbsp;:&nbsp;{{$client_details->mobile_no}}</font>
                </td>
                <td align='right'><b>Last payment date </b> </td>
                <td >
                    <font>&nbsp;:&nbsp;{{$invoice_data->last_payment_date}}</font>
                </td>
            </tr>
        </table>

        <table align="center">
            <tbody >
                <tr>
                    <td><h2>Invoice/Bill</h2></td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="1" width="95%" align="center" style="border-collapse:collapse; margin:2px auto; padding-top: 50px">

 
            <tr>
                <td height='22' style="width:5%text-align:center;font-size:12px;padding:5px;">SL</td>
                <td height='22' style="width:50%;text-align:center;font-size:12px;"><font> Description</font></td>
                <td height='22' style="text-align:center;font-size:12px;"> Quantity </td>
                <td height='22' style="text-align:center;font-size:12px;"> Unit price </td>
                <td height='22' style="text-align:center;font-size:12px;"> Total </td>
            </tr>

            

            <tr>
                <td valign='top' style='font-size:14px;text-align:center;'>
                    @php $sr = 1; @endphp

                    @foreach($voucher_data['data'] as $value)
                        {{ $sr++ }}<br>
                    @endforeach

                </td>
                <td style="height:100px;text-align:left;;font-size:14px;padding-top:5px;" valign='top'>
                    @foreach($voucher_data['data'] as $row)
                        &nbsp;&nbsp;&nbsp;&nbsp;{{$row->name}}&nbsp;&nbsp;({{$row->year}})<br>
                    @endforeach
                     
                    @if($invoice_data->discount>0)
                        &nbsp;&nbsp;&nbsp;&nbsp;Discount
                    @endif
                </td>

                <td valign='top' style="text-align:right;">
                    @foreach($voucher_data['data'] as $row)
                        @if($row->acc_no == 13)
                            {{number_format($row->rate/0.3)}}&nbsp;<br>
                        @elseif($row->acc_no == 14)
                            {{1}}&nbsp;<br>
                        @elseif($row->acc_no == 15)
                            {{1}}&nbsp;<br>
                        @else
                            {{$fee_data->quantity}}&nbsp;<br>
                        @endif
                    @endforeach
                    @if($invoice_data->discount>0)
                        -&nbsp;&nbsp;
                    @endif

                </td>
                <td valign='top' style="text-align:right;">
                    @foreach($voucher_data['data'] as $row)
                        @if($row->acc_no == 13)
                            {{number_format((float).3, 2, '.', '')}}&nbsp;<br>
                        @elseif($row->acc_no == 14)
                            {{$row->rate}}&nbsp;<br>
                        @elseif($row->acc_no == 15)
                            {{$row->rate}}&nbsp;<br>
                        @else
                            {{$fee_data->fee_amount}}&nbsp;<br>
                        @endif
                    @endforeach
                    @if($invoice_data->discount>0)
                        -&nbsp;&nbsp;
                    @endif
                </td>
                <td valign='top' style="text-align:right;">
                    @foreach($voucher_data['data'] as $row)
                        {{$row->rate}}&nbsp;&nbsp;<br>
                    @endforeach
                    @if($invoice_data->discount>0)
                        (-){{$invoice_data->discount}}&nbsp;&nbsp;
                    @endif
                </td>
            </tr>

        

           

            <tr>
                <td colspan='3' height='23' style="text-align:left; font-size:14px;">  &nbsp;&nbsp; <!-- <?php echo Converter::en2bn($invoice_data->net_amount) ?> --></td>
                <td height='23' style="text-align:right; font-size:14px;"> Total amount &nbsp;&nbsp;</td>
                <td height='23' style="text-align:right; font-size:14px;">{{$invoice_data->net_amount}}&nbsp;&nbsp;</td> 
           </tr>
        </table>

        <table border="0" width="95%" align="center" style="border-collapse:collapse; margin:60px 0px auto;">

            <tr>
                <td  style="text-align:center; font-weight:normal; font-size:14px; color:black;">
                    <span style="border-top: 1px dotted;">Prepared by</span><br />
                </td>
                
                <td  style="text-align:center; font-weight:normal; font-size:14px; color:black;">
                    <span style="border-top: 1px dotted;">Received by</span><br />
                </td>
            </tr>  
            
        </table>

      
        <table border="0" width="95%" align="center" style="border-collapse:collapse; margin:2px auto;">
            <tr>
                <td style="border-bottom: 2px dotted;">
                    
                </td>
            </tr>
        </table>
        <br/>

        <!----------------second part----------------->


        <table border="0" width="95%" align="center" style="border-collapse:collapse; margin:2px auto; padding-top: 50px">
            <tr>
                <td style="width:1.5in; text-align:center;"><!-- <img src="" height="80px" width="80px" /> --></td>

                <td style="text-align:center;">
                    <font style="font-size:25px; font-weight:bold; color:blue;"></font>

                    <!-- <br /> -->

                    <font style="font-size:16px; font-weight:bold;">
                        
                        <!-- <br> -->
                        (Client Copy)
                    </font>

                </td>

                <td style="width:1.2in; text-align:left;">
                    
                    <!-- <img src="" height="80px" width="80px" style="position:relative;right:10px;" /> -->
                    
                </td>

            </tr>
        </table> 

        <table border="0" width="95%" align="center" style="border-collapse:collapse; margin:10px auto;">
            
  
            <tr>
                <td style="padding-left: 50px;width: 150px;"><b>Client name </b></td>
                <td>
                    <font>&nbsp;:&nbsp;{{$client_details->name}}</font>
                </td>

               <td align='right'><b>Invoice no </b> </td>
                <td>
                    <font>&nbsp;:&nbsp;{{$invoice_data->invoice_id}}</font>
                </td>

            </tr>
            <tr>
                <td style="padding-left: 50px"><b>Address</b> </td>
                <td>
                    <font>&nbsp;:&nbsp;{{$client_details->address}}</font>
                </td>
                <td align='right'><b>Generated date </b> </td>
                <td>
                    <font>&nbsp;:@php
                                $date = $invoice_data->created_at;
                                $credate = substr($date,0,10);
                                @endphp
                                {{$credate}}</font>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 50px"><b>Mobile no</b></td>
                <td>
                    <font>&nbsp;:&nbsp;{{$client_details->mobile_no}}</font>
                </td>
                <td align='right'><b>Last payment date </b> </td>
                <td >
                    <font>&nbsp;:&nbsp;{{$invoice_data->last_payment_date}}</font>
                </td>
            </tr>
        </table>

        <table align="center">
            <tbody >
                <tr>
                    <td><h2>Invoice/Bill</h2></td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="1" width="95%" align="center" style="border-collapse:collapse; margin:2px auto; padding-top: 50px">

 
            <tr>
                <td height='22' style="width:5%text-align:center;font-size:12px;padding:5px;">SL</td>
                <td height='22' style="width:50%;text-align:center;font-size:12px;"><font> Description</font></td>
                <td height='22' style="text-align:center;font-size:12px;"> Quantity </td>
                <td height='22' style="text-align:center;font-size:12px;"> Unit price </td>
                <td height='22' style="text-align:center;font-size:12px;"> Total </td>
            </tr>

            

            <tr>
                <td valign='top' style='font-size:14px;text-align:center;'>
                    @php $sr = 1; @endphp

                    @foreach($voucher_data['data'] as $value)
                        {{ $sr++ }}<br>
                    @endforeach

                </td>
                <td style="height:100px;text-align:left;;font-size:14px;padding-top:5px;" valign='top'>
                    @foreach($voucher_data['data'] as $row)
                        &nbsp;&nbsp;&nbsp;&nbsp;{{$row->name}}&nbsp;&nbsp;({{$row->year}})<br>
                    @endforeach
                     
                    @if($invoice_data->discount>0)
                        &nbsp;&nbsp;&nbsp;&nbsp;Discount
                    @endif
                </td>

                <td valign='top' style="text-align:right;">
                    @foreach($voucher_data['data'] as $row)
                        @if($row->acc_no == 13)
                            {{number_format($row->rate/0.3)}}&nbsp;<br>
                        @elseif($row->acc_no == 14)
                            {{1}}&nbsp;<br>
                        @elseif($row->acc_no == 15)
                            {{1}}&nbsp;<br>
                        @else
                            {{$fee_data->quantity}}&nbsp;<br>
                        @endif
                    @endforeach
                    @if($invoice_data->discount>0)
                        -&nbsp;&nbsp;
                    @endif

                </td>
                <td valign='top' style="text-align:right;">
                    @foreach($voucher_data['data'] as $row)
                        @if($row->acc_no == 13)
                            {{number_format((float).3, 2, '.', '')}}&nbsp;<br>
                        @elseif($row->acc_no == 14)
                            {{$row->rate}}&nbsp;<br>
                        @elseif($row->acc_no == 15)
                            {{$row->rate}}&nbsp;<br>
                        @else
                            {{$fee_data->fee_amount}}&nbsp;<br>
                        @endif
                    @endforeach
                    @if($invoice_data->discount>0)
                        -&nbsp;&nbsp;
                    @endif
                </td>
                <td valign='top' style="text-align:right;">
                    @foreach($voucher_data['data'] as $row)
                        {{$row->rate}}&nbsp;&nbsp;<br>
                    @endforeach
                    @if($invoice_data->discount>0)
                        (-){{$invoice_data->discount}}&nbsp;&nbsp;
                    @endif
                </td>
            </tr>

        

           

            <tr>
                <td colspan='3' height='23' style="text-align:left; font-size:14px;">  &nbsp;&nbsp; <!-- <?php echo Converter::en2bn($invoice_data->net_amount) ?> --></td>
                <td height='23' style="text-align:right; font-size:14px;"> Total amount &nbsp;&nbsp;</td>
                <td height='23' style="text-align:right; font-size:14px;">{{$invoice_data->net_amount}}&nbsp;&nbsp;</td> 
           </tr>
        </table>

        <table border="0" width="95%" align="center" style="border-collapse:collapse; margin:60px 0px auto;">

            <tr>
                <td  style="text-align:center; font-weight:normal; font-size:14px; color:black;">
                    <span style="border-top: 1px dotted;">Prepared by</span><br />
                </td>
                
                <td  style="text-align:center; font-weight:normal; font-size:14px; color:black;">
                    <span style="border-top: 1px dotted;">Received by</span><br />
                </td>
            </tr>  
            
        </table>

        <table border="0" width="100%" align="center" style="border-collapse:collapse; margin:60px 0px auto;">
            <tr>
                <td><img src="{{ asset('/')}}assets/images/pade.PNG" height="26%" width="100%" /></td>
            </tr>
            
        </table>

</body>
</html>