<html>
<head>
    <base href="" />
    <title>Money Receipt </title>
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
                    <font>&nbsp;:&nbsp;  {{$client_details->name}}</font>
                </td>

               <td align='right'><b>Transaction  no </b> </td>
                <td>
                    <font>&nbsp;:&nbsp; {{$transaction_data->trans_id}}</font>
                </td>

            </tr>
            <tr>
                <td style="padding-left: 50px"><b>Address</b> </td>
                <td>
                    <font>&nbsp;:&nbsp;   {{$client_details->address}} </font>
                </td>
                <td align='right'><b>Payment date </b> </td>
                <td>
                    <font>&nbsp;: {{ date("d-m-Y", strtotime($transaction_data->payment_date)) }} </font>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 50px"><b>Mobile no</b></td>
                <td>
                    <font>&nbsp;:&nbsp;  {{$client_details->mobile_no}} </font>
                </td>
                <td align='right'> </td>
                <td >
                    <font>&nbsp;&nbsp;  </font>
                </td>
            </tr>
        </table>

        <table align="center">
            <tbody >
                <tr>
                    <td><h2> Money Receipt </h2></td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="1" width="95%" align="center" style="border-collapse:collapse; margin:2px auto; padding-top: 50px">

 
            <tr>
                <td height='22' style="width:5%text-align:center;font-size:12px;padding:5px;">SL</td>
                <td colspan='3' height='22' style="width:85%;text-align:center;font-size:12px;">
                    <font> Description</font>
                </td>
                <td height='22' style="width:10%; text-align:center;font-size:12px;"> Amount  </td>
            </tr>

            <tr>
                <td valign='top' style='font-size:14px;text-align:center;'>
                    1
                </td>
                <td colspan='3' style="height:100px;text-align:left;;font-size:14px;padding-top:5px;" valign='top'>
                    &nbsp;&nbsp; {{$transaction_data->remark}} 
                </td>

                <td valign='top' style="text-align:right;">
                    {{$transaction_data->total_amount}}  &nbsp;&nbsp; 
                </td>
            </tr>

            <tr>
                <td colspan='3' height='23' style="text-align:left; font-size:14px;">  &nbsp;&nbsp; </td>
                <td height='23' style="text-align:right; font-size:14px;"> Total amount &nbsp;&nbsp;</td>
                <td height='23' style="text-align:right; font-size:14px;"> {{$transaction_data->total_amount}} &nbsp;&nbsp; </td> 
           </tr>
        </table>

        <table border="0" width="95%" align="center" style="border-collapse:collapse; margin:60px 0px auto;">

            <tr>
                <td  style="text-align:center; font-weight:normal; font-size:14px; color:black;">
                  
                </td>
                
                <td  style="text-align:right; font-weight:normal; font-size:14px; color:black;">
                    <p style="">{{ $user_data->name}}</p><br />
                    <span style=" border-top: 1px dotted;">Received by</span><br />
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
                    <font>&nbsp;:&nbsp;{{$client_details->name}} </font>
                </td>

               <td align='right'><b>Transaction no </b> </td>
                <td>
                    <font>&nbsp;:&nbsp;{{$transaction_data->trans_id}}</font>
                </td>

            </tr>
            <tr>
                <td style="padding-left: 50px"><b>Address</b> </td>
                <td>
                    <font>&nbsp;:&nbsp; {{$client_details->address}} </font>
                </td>
                <td align='right'><b>Payment date  </b> </td>
                <td>
                    <font>&nbsp;: {{ date("d-m-Y", strtotime($transaction_data->payment_date))}}
                    </font>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 50px"><b>Mobile no</b></td>
                <td>
                    <font>&nbsp;:&nbsp; {{$client_details->mobile_no}}</font>
                </td>
                <td align='right'><b> </b> </td>
                <td >
                    <font>&nbsp;&nbsp; </font>
                </td>
            </tr>
        </table>

        <table align="center">
            <tbody >
                <tr>
                    <td><h2>Money Receipt</h2></td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="1" width="95%" align="center" style="border-collapse:collapse; margin:2px auto; padding-top: 50px">

 
            <tr>
                <td height='22' style="width:5%text-align:center;font-size:12px;padding:5px;">SL</td>
                <td  colspan="3" height='22' style="width:85%;text-align:center;font-size:12px;"><font> Description</font></td>
                <td height='22' style="width:10%;text-align:center;font-size:12px;"> Total </td>
            </tr>

            

            <tr>
                <td valign='top' style='font-size:14px;text-align:center;'>
                1
                </td>
                <td colspan="3" style="height:100px;text-align:left;;font-size:14px;padding-top:5px;" valign='top'>
                    &nbsp;&nbsp; {{ $transaction_data->remark}}
                </td>

                
                <td valign='top' style="text-align:right;">
                  {{$transaction_data->total_amount}} &nbsp;&nbsp;
                </td>
            </tr>

            <tr>
                <td colspan='3' height='23' style="text-align:left; font-size:14px;">  &nbsp;&nbsp; </td>
                <td height='23' style="text-align:right; font-size:14px;"> Total amount &nbsp;&nbsp;</td>
                <td height='23' style="text-align:right; font-size:14px;"> {{$transaction_data->total_amount}} &nbsp;&nbsp;</td> 
           </tr>
        </table>

        <table border="0" width="95%" align="center" style="border-collapse:collapse; margin:60px 0px auto;">

            <tr>
                <td  style="text-align:center; font-weight:normal; font-size:14px; color:black;">

                </td>
                
                <td  style="text-align:right; font-weight:normal; font-size:14px; color:black;">
                    <p style=" margin-bottom: 5px">{{ $user_data->name}}</p><br />
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