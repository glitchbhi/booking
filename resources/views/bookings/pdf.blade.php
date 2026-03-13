<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking {{ $booking->booking_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 10px;
            font-size: 12px;
        }
        .status-booked {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-ongoing {
            background-color: #dcfce7;
            color: #15803d;
        }
        .status-completed {
            background-color: #f3f4f6;
            color: #374151;
        }
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            color: #000;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        .grid.full {
            grid-template-columns: 1fr;
        }
        .field {
            margin-bottom: 12px;
        }
        .field-label {
            font-weight: 600;
            color: #666;
            font-size: 13px;
        }
        .field-value {
            color: #000;
            font-size: 14px;
            margin-top: 4px;
        }
        .amount {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }
        .amount-label {
            font-size: 14px;
            font-weight: 600;
            color: #666;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .payment-proof-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .payment-proof-section img {
            max-width: 100%;
            height: auto;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            margin-top: 10px;
        }
        .notes {
            background-color: #f9fafb;
            padding: 15px;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
            font-size: 13px;
            color: #555;
        }
        @media print {
            body {
                background: white;
            }
            .container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmation</h1>
            <p>Booking #: <strong>{{ $booking->booking_number }}</strong></p>
            <span class="status-badge status-{{ strtolower($booking->status) }}">
                {{ ucfirst($booking->status) }}
            </span>
            <p style="margin-top: 10px; font-size: 12px;">Generated on: {{ now()->format('F d, Y \a\t h:i A') }}</p>
        </div>

        <div class="section">
            <h2>Ground Information</h2>
            <div class="grid">
                <div>
                    <div class="field">
                        <div class="field-label">Ground Name</div>
                        <div class="field-value">{{ $booking->ground->name }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Location</div>
                        <div class="field-value">{{ $booking->ground->location }}</div>
                    </div>
                </div>
                <div>
                    <div class="field">
                        <div class="field-label">Sport Type</div>
                        <div class="field-value">{{ $booking->ground->sportType->name }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Owner</div>
                        <div class="field-value">{{ $booking->ground->owner->name }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Booking Details</h2>
            <div class="grid">
                <div>
                    <div class="field">
                        <div class="field-label">Start Time</div>
                        <div class="field-value">{{ $booking->start_time->format('M d, Y h:i A') }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">End Time</div>
                        <div class="field-value">{{ $booking->end_time->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                <div>
                    <div class="field">
                        <div class="field-label">Duration</div>
                        <div class="field-value">{{ $booking->duration_hours }} hours</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Booked On</div>
                        <div class="field-value">{{ $booking->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Payment Details</h2>
            <div class="grid full">
                <div>
                    <div class="field">
                        <div class="field-label">Rate per Hour</div>
                        <div class="field-value">BTN {{ number_format($booking->ground->rate_per_hour, 2) }}</div>
                    </div>
                </div>
                <div>
                    <div class="field">
                        <div class="field-label">Duration</div>
                        <div class="field-value">{{ $booking->duration_hours }} hours</div>
                    </div>
                </div>
                <div>
                    <div class="field">
                        <div class="amount-label">Total Amount</div>
                        <div class="amount">BTN {{ number_format($booking->total_amount, 2) }}</div>
                    </div>
                </div>
            </div>
            @if($booking->status === 'cancelled' && $booking->refund_amount > 0)
                <div class="field" style="margin-top: 15px; color: #16a34a;">
                    <div class="field-label" style="color: #16a34a;">Refund Amount</div>
                    <div class="field-value" style="color: #16a34a; font-weight: bold;">BTN {{ number_format($booking->refund_amount, 2) }}</div>
                </div>
            @endif
        </div>

        @if($booking->notes)
            <div class="section">
                <h2>Notes</h2>
                <div class="notes">
                    {{ $booking->notes }}
                </div>
            </div>
        @endif

        @if($booking->payment_proof)
            <div class="payment-proof-section">
                <h3 style="margin-bottom: 10px; font-weight: bold;">Payment Proof</h3>
                <p style="font-size: 12px; color: #666; margin-bottom: 10px;">
                    <i>Payment screenshot uploaded</i>
                </p>
                <img src="{{ asset('storage/' . $booking->payment_proof) }}" alt="Payment Proof">
            </div>
        @endif

        @if($booking->review)
            <div class="section">
                <h2>Your Review</h2>
                <div class="field">
                    <div class="field-label">Rating</div>
                    <div class="field-value">{{ $booking->review->rating }}/5 ★</div>
                </div>
                @if($booking->review->comment)
                    <div class="field">
                        <div class="field-label">Comment</div>
                        <div class="field-value">{{ $booking->review->comment }}</div>
                    </div>
                @endif
            </div>
        @endif

        <div class="footer">
            <p>This is a confirmation of your booking. Please keep this for your records.</p>
            <p style="margin-top: 10px;">For support, please contact the ground owner: <strong>{{ $booking->ground->owner->name }}</strong></p>
        </div>
    </div>

    <script>
        // Auto-trigger print dialog when page loads (optional)
        window.addEventListener('load', function() {
            // Uncomment to auto-print on load
            // window.print();
        });
    </script>
</body>
</html>
