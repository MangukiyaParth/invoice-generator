<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 10px; size: A4; }
        * { box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.2;
            margin: 15px;
            padding: 5px;
        }
        h1, h2, h3, h4, h5 { margin: 0; padding: 0; }

        .container { max-width: 100%; margin: 0 auto; }
        
        .header {
            width: 100%;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header-table td {
            vertical-align: top;
            padding: 0;
        }
        
        .company-cell {
            width: 60%;
        }
        
        .invoice-cell {
            width: 40%;
            text-align: right;
        }

        .company-info h3, .bill-to h2 {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 2px;
        }
        .company-info p, .bill-to p {
            margin: 1px 0;
            font-size: 12px;
            color: #555;
        }

        .invoice-title h1 {
            font-size: 28px;
            color: #2ea043;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .invoice-number {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }
        .balance-due {
            /* background-color: #f8f9fa; */
            padding: 5px;
            /* border-radius: 3px; */
           
        }
        .balance-due .label {
            font-size: 12px;
            color: #666;
        }
        .balance-due .amount {
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
        }

        .billing-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }
        
        .billing-table td {
            vertical-align: top;
            padding: 0;
        }
        
        .bill-to-cell {
            width: 50%;
            padding-right: 10px;
        }
        
        .invoice-details-cell {
            width: 50%;
            text-align: right;
            padding-left: 10px;
            vertical-align: top;
        }
        
        .bill-to h3, .invoice-details h3 {
            font-size: 14px;
            margin-bottom: 2px;
            color: #2c3e50;
            font-weight: bold;
        }
        .bill-to p {
            margin: 1px 0;
            font-size: 12px;
            color: #555;
        }
        .invoice-details {
            display: inline-block;
            text-align: right;
            margin-top: 5px;
        }

        .invoice-details-cell p {
            margin: 3px 0;
            font-size: 12px;
            color: #555;
        }

        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .items-table th {
            background-color: #2ea043;
            color: #2c3e50;
            padding: 6px 4px;
            text-align: left;
            font-size: 13px;
            font-weight: bold;
            border: 1px solid #c0c8cf;
            vertical-align: middle;
        }
        .items-table td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            font-size: 13px;
            vertical-align: top;
            background-color: #fff;
        }
        .items-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .totals-section {
            margin-top: 0;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 4px 8px;
            font-size: 12px;
            border-bottom: 1px solid #eee;
        }
        .totals-table .label {
            text-align: left;
            color: #666;
        }
        .totals-table .amount {
            text-align: right;
            font-weight: bold;
            color: #2c3e50;
        }
        .total-row {
            background-color: #2ea043;
            color: #fff;
        }

        .total-row .label,
        .total-row .amount {
            color: #2c3e50;
            font-size: 11px;
            font-weight: bold;
        }
        .payment-made {
            color: #e74c3c !important;
        }

        .total-words {
            margin-top: 10px;
            padding: 8px;
            clear: both;
        }
        
        .notes-section, .terms-section {
            margin-top: 5px;
            page-break-inside: avoid;
        }
        .notes-section h3, .terms-section h3 {
            font-size: 14px;
            margin-bottom: 5px;
            color: #2c3e50;
            padding-bottom: 2px;
        }
        .terms-section p, .notes-section p {
            font-size: 11px;
            line-height: 1.5;
            color: #555;
        }

        .footer {
            position: fixed;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        
        .logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    @php
        $subTotal = 0;
        $totalIgst = 0;
        $totalSgst = 0;
        $totalCgst = 0;
        foreach($invoice->items ?? [] as $item) {
            $qty = (float)$item->quantity;
            $rate = (float)$item->rate;
            $itemTotal = $qty * $rate;
            $subTotal += $itemTotal;
            
            // Calculate tax based on tax_type
            if ($item->tax_type === 'igst') {
                $totalIgst += ($itemTotal * 18) / 100;
            } elseif ($item->tax_type === 'sgst_cgst') {
                $totalSgst += ($itemTotal * 9) / 100;
                $totalCgst += ($itemTotal * 9) / 100;
            }
        }
        $totalTax = $totalIgst + $totalSgst + $totalCgst;
        $grandTotal = $subTotal + $totalTax;
        $paidTotal = $invoice->paid_amount ?? '0';
        $dueTotal = $grandTotal - $paidTotal;
        $currencySymbol = ($invoice->currency === 'INR') ? '₹' : (($invoice->currency === 'USD') ? '$' : (($invoice->currency === 'AUD') ? 'AU$' : ''));
        
        function numberToWords($amount, $currency) {
            $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
            $teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
            $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            
            if ($amount == 0) return 'Zero Only';
            
            $rupees = floor($amount);
            $paise = round(($amount - $rupees) * 100);
            
            $result = '';
            
            if ($rupees >= 10000000) {
                $crores = floor($rupees / 10000000);
                if ($crores >= 100) {
                    $result .= $ones[floor($crores / 100)] . ' Hundred ';
                    $crores %= 100;
                }
                if ($crores >= 20) {
                    $result .= $tens[floor($crores / 10)] . ' ';
                    $crores %= 10;
                } else if ($crores >= 10) {
                    $result .= $teens[$crores - 10] . ' ';
                    $crores = 0;
                }
                if ($crores > 0) {
                    $result .= $ones[$crores] . ' ';
                }
                $result .= 'Crore ';
                $rupees %= 10000000;
            }
            
            if ($rupees >= 100000) {
                $lakhs = floor($rupees / 100000);
                if ($lakhs >= 20) {
                    $result .= $tens[floor($lakhs / 10)] . ' ';
                    $lakhs %= 10;
                } else if ($lakhs >= 10) {
                    $result .= $teens[$lakhs - 10] . ' ';
                    $lakhs = 0;
                }
                if ($lakhs > 0) {
                    $result .= $ones[$lakhs] . ' ';
                }
                $result .= 'Lakh ';
                $rupees %= 100000;
            }
            
            if ($rupees >= 1000) {
                $thousands = floor($rupees / 1000);
                if ($thousands >= 100) {
                    $result .= $ones[floor($thousands / 100)] . ' Hundred ';
                    $thousands %= 100;
                }
                if ($thousands >= 20) {
                    $result .= $tens[floor($thousands / 10)] . ' ';
                    $thousands %= 10;
                } else if ($thousands >= 10) {
                    $result .= $teens[$thousands - 10] . ' ';
                    $thousands = 0;
                }
                if ($thousands > 0) {
                    $result .= $ones[$thousands] . ' ';
                }
                $result .= 'Thousand ';
                $rupees %= 1000;
            }
            
            if ($rupees > 0) {
                if ($rupees >= 100) {
                    $result .= $ones[floor($rupees / 100)] . ' Hundred ';
                    $rupees %= 100;
                }
                if ($rupees >= 20) {
                    $result .= $tens[floor($rupees / 10)] . ' ';
                    $rupees %= 10;
                } else if ($rupees >= 10) {
                    $result .= $teens[$rupees - 10] . ' ';
                    $rupees = 0;
                }
                if ($rupees > 0) {
                    $result .= $ones[$rupees] . ' ';
                }
            }
            
            $currencyName = $currency === 'USD' ? 'Dollar' : ($currency === 'AUD' ? 'Dollar' : 'Rupee');
            $subUnit = $currency === 'USD' ? 'Cent' : ($currency === 'AUD' ? 'Cent' : 'Paise');
            
            $result = ($currency === 'USD' ? 'US ' : ($currency === 'AUD' ? 'Australian ' : 'Indian ')) . $currencyName . ' ' . trim($result);
            
            if ($paise > 0) {
                $paiseWords = '';
                if ($paise >= 20) {
                    $paiseWords .= $tens[floor($paise / 10)] . ' ';
                    $paise %= 10;
                } else if ($paise >= 10) {
                    $paiseWords .= $teens[$paise - 10] . ' ';
                    $paise = 0;
                }
                if ($paise > 0) {
                    $paiseWords .= $ones[$paise] . ' ';
                }
                $result .= ' and ' . trim($paiseWords) . $subUnit;
            }
            
            return $result . ' Only';
        }
    @endphp

    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="company-cell">
                        @if(!empty($invoice->company_name->logo))
                            <img src="{{ public_path('storage/'.$invoice->company_name->logo) }}" class="logo">
                        @endif
                         <div class="company-info">
                            <h3>{{ strtoupper($invoice->company_name->name ?? '') }}</h3>
                            @php
                                $addressParts = array_filter([
                                    $invoice->company_name->address ?? '',
                                    $invoice->company_name->city ?? '',
                                    $invoice->company_name->state ?? '',
                                    $invoice->company_name->country ?? '',
                                    $invoice->company_name->zip_code ?? ''
                                ]);
                            @endphp
                            @if(!empty($addressParts))<p><strong>Address:</strong> {{ implode(' ', $addressParts) }}</p>@endif
                            @if(!empty( $invoice->company_name->email ))<p><strong>Email:</strong> {{ $invoice->company_name->email ?? '' }}</p>@endif
                            @if(!empty( $invoice->company_name->gst_number ))<p><strong>GST Number:</strong> {{ $invoice->company_name->gst_number ?? '' }}</p>@endif
                        </div>
                    </td>
                    <td class="invoice-cell">
                        <div class="invoice-title">
                            <h1>INVOICE</h1>
                            <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                            <div class="balance-due">
                                <div class="label">Balance Due</div>
                                <div class="{{ $dueTotal > 0 ? 'amount' : '' }}">{{ $currencySymbol }}{{ number_format((float)$dueTotal, 2) }}</div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- BILL TO SECTION -->
        <table class="billing-table">
            <tr>
                <td class="bill-to-cell">
                    <div class="bill-to">
                        <h4>Bill To</h4>
                        <h3>{{ strtoupper($invoice->customer_name->name ?? '') }}</h3>
                        @php
                            $customerAddressParts = array_filter([
                                $invoice->customer_name->address ?? '',
                                $invoice->customer_name->city ?? '',
                                $invoice->customer_name->state ?? '',
                                $invoice->customer_name->country ?? '',
                                $invoice->customer_name->zip_code ?? ''
                            ]);
                        @endphp
                        @if(!empty($customerAddressParts))<p><strong>Address:</strong> {{ implode(' ', $customerAddressParts) }}</p>@endif
                        @if(!empty( $invoice->customer_name->email ))<p><strong>Email:</strong> {{ $invoice->customer_name->email ?? '' }}</p>@endif
                        @if(!empty( $invoice->customer_name->gst_number ))<p><strong>GST Number:</strong> {{ $invoice->customer_name->gst_number ?? '' }}</p>@endif
                        @if(!empty( $invoice->customer_name->place_of_supply ))<p><strong>Place of Supply:</strong> {{ $invoice->customer_name->place_of_supply ?? '' }}</p>@endif
                    </div>
                </td>
                <td class="invoice-details-cell">
                    <p><strong>Invoice Date:</strong>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y') }}</p>
                    <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') }}</p>
                    @if(!empty( $invoice->terms ))<p><strong>Terms:</strong> {{ $invoice->terms ?? '--'}}</p>@endif
                    @if(!empty( $invoice->company_name->lut_number ))<p><strong>LUT Number:</strong> {{ $invoice->company_name->lut_number ?? '' }}</p>@endif
                    @if(!empty( $invoice->company_name->euid_number ))<p><strong>EUID Number:</strong> {{ $invoice->company_name->euid_number ?? '' }}</p>@endif
                </td>
            </tr>
        </table>
        <!-- ITEMS TABLE -->
        <table class="items-table"> 
            <thead>
                <tr>
                    <th style="width:5%; text-align:center;">No</th>
                    <th style="width:35%; text-align:center;">Description</th>
                    <th style="width:10%; text-align:center;">HSN/SAC</th>
                    <th style="width:8%; text-align:center;">Qty</th>
                    <th style="width:12%; text-align:center;">Rate </th>
                    <th style="width:12%; text-align:center;">Tax</th>
                    <th style="width:15%; text-align:center;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items ?? [] as $key => $item)
                    @php
                        $qty = (float)$item->quantity;
                        $rate = (float)$item->rate;
                        $itemTotal = $qty * $rate;
                        $taxAmount = 0;
                        $taxLabel = 'No Tax';
                        
                        if ($item->tax_type === 'igst') {
                            $taxAmount = ($itemTotal * 18) / 100;
                            $taxLabel = 'IGST (18%)';
                        } elseif ($item->tax_type === 'sgst_cgst') {
                            $taxAmount = ($itemTotal * 18) / 100; // 9% SGST + 9% CGST
                            $taxLabel = 'SGST+CGST (9%+9%)';
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $item->description }}</td>
                        <td class="text-center">{{ $item->hsn ?? '-' }}</td>
                        <td class="text-center">{{ number_format((float)$qty, 2) }}</td>
                        <td class="text-center">{{ $currencySymbol }}{{ number_format((float)$rate, 2) }}</td>
                        <td class="text-center">
                            @if($taxAmount > 0)
                                {{ $currencySymbol }}{{ number_format((float)$taxAmount, 2) }}
                                <br><small style="color: #666;">{{ $taxLabel }}</small>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center"><strong>{{ $currencySymbol }}{{ number_format((float)$item->total_amount, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    <!-- BANK DETAILS AND TOTAL SECTION -->
        <div style="width: 100%; margin-top: 15px; display: table;">
            <!-- Bank Details -->
            <div style="width: 48%; float: left; padding-right: 2%;">
                @if($invoice->company_name->bank_details)
                <div style="border: 1px solid #ddd; padding: 15px; background-color: #f9f9f9; min-height: 120px;">
                    <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #2c3e50;">Bank Details</h4>
                    <div style="font-size: 12px; line-height: 1.5;">{!! nl2br(e($invoice->company_name->bank_details)) !!}</div>
                </div>
                @endif
            </div>

            <!-- TOTAL SECTION -->
            <div class="totals-section"  style="width: 50%; float: right;">
                <table class="totals-table">
                    <tr>
                        <td class="label">Sub Total:</td>
                        <td class="amount">{{ $currencySymbol }}{{ number_format((float)$subTotal, 2) }}</td>
                    </tr>
                    @if($totalIgst > 0)
                    <tr>
                        <td class="label">IGST (18%):</td>
                        <td class="amount">{{ $currencySymbol }}{{ number_format((float)$totalIgst, 2) }}</td>
                    </tr>
                    @endif
                    @if($totalSgst > 0)
                    <tr>
                        <td class="label">SGST (9%):</td>
                        <td class="amount">{{ $currencySymbol }}{{ number_format((float)$totalSgst, 2) }}</td>
                    </tr>
                    @endif
                    @if($totalCgst > 0)
                    <tr>
                        <td class="label">CGST (9%):</td>
                        <td class="amount">{{ $currencySymbol }}{{ number_format((float)$totalCgst, 2) }}</td>
                    </tr>
                    @endif
                    @if($totalTax > 0)
                    <tr>
                        <td class="label">Total Tax:</td>
                        <td class="amount">{{ $currencySymbol }}{{ number_format((float)$totalTax, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td class="label">Total Amount:</td>
                        <td class="amount">{{ $currencySymbol }}{{ number_format((float)$grandTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Payment Made:</td>
                        <td class="amount payment-made">(-) {{ $currencySymbol }}{{ number_format((float)$invoice->paid_amount ?? '0', 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Balance Due:</td>
                        <td class="amount">{{ $currencySymbol }}{{ number_format((float)$dueTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total Amount in Words:</td>
                        <td class="amount"><em>{{ numberToWords($grandTotal, $invoice->currency) }}</em></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="total-words">
          <!---->
        </div>

         <!-- NOTES -->
        @if($invoice->company_name->notes)
        <div class="notes-section" style="width:100%; text-align:center;">
            <p>{{ $invoice->company_name->notes }}</p>
        </div>
        @endif

        <!-- TERMS -->
        @if($invoice->company_name->terms_conditions)
        <div class="terms-section" style="width:100%; text-align:center;">
            <p>{{ $invoice->company_name->terms_conditions }}</p>
        </div>
        @endif
    </div>
</body>
</html>