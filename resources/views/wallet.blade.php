<!DOCTYPE html>
<html lang="bn">
<head>
    <title>My Wallet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --primary-color: #6a00f4; --bg-color: #f0f1f6; --card-bg: white; --success: #4CAF50; --error: #F44336; }
        body { font-family: 'Roboto', sans-serif; background-color: var(--bg-color); margin: 0; padding: 15px; padding-bottom: 85px; }
        .balance-card { background: linear-gradient(135deg, #ff6a6a, #cc0000); color: white; padding: 25px; border-radius: 20px; text-align: center; margin-bottom: 20px; box-shadow: 0 10px 25px rgba(123, 44, 191, 0.3); }
        .balance-card h4 { margin: 0 0 10px; font-size: 1em; opacity: 0.8; text-transform: uppercase; }
        .balance-card .amount { font-size: 2.5em; font-weight: 700; margin: 0; }
        .balance-card .sub-balance { font-size: 0.9em; opacity: 0.9; margin-top: 10px; }
        .actions { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 25px; }
        .action-btn { background-color: var(--card-bg); padding: 12px 5px; border-radius: 12px; text-align: center; border: none; font-size: 0.9em; font-weight: 500; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .action-btn:active { transform: scale(0.95); }
        .action-btn i { font-size: 20px; margin-bottom: 8px; display: block; }
        .history-section h3 { margin-bottom: 15px; }
        .txn-item { display: flex; justify-content: space-between; align-items: center; background-color: var(--card-bg); padding: 15px; border-radius: 10px; margin-bottom: 10px; }
        .txn-details p { margin: 0; }
        .txn-amount { font-weight: 500; font-size: 1.1em; }
        .txn-amount.deposit { color: #388e3c; } .txn-amount.withdraw { color: #d32f2f; }
        .txn-status-wrapper { text-align: right; }
        .txn-status { font-size: 0.8em; font-weight: 500; text-transform: uppercase; padding: 3px 8px; border-radius: 20px; color: white; display: inline-block; margin-top: 4px; }
        .txn-status.pending { background-color: #f57c00; } .txn-status.complete, .txn-status.completed { background-color: #388e3c; } .txn-status.failed, .txn-status.rejected { background-color: #d32f2f; }
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); animation: fadeIn 0.3s; justify-content: center; align-items: flex-end; }
        .modal-content { width: 100%; background-color: var(--bg-color); border-top-left-radius: 20px; border-top-right-radius: 20px; padding: 20px; padding-bottom: 30px; box-sizing: border-box; animation: slideUp 0.3s; max-height: 90vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h2 { margin: 0; }
        .close { font-size: 28px; font-weight: bold; cursor: pointer; color: #888; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 1em; }
        .modal-btn { width: 100%; padding: 15px; border: none; border-radius: 10px; background: var(--primary-color); color: white; font-size: 18px; font-weight: 700; cursor: pointer; transition: background-color 0.2s; }
        .payment-info { background-color: #e8eaf6; padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 15px; }
        .payment-info strong { font-size: 1.2em; color: var(--primary-color); }
        .info-msg { padding: 10px; text-align: center; background: #fffde7; border-radius: 8px; margin-top: 15px; }
        .animation-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 3000; justify-content: center; align-items: center; }
        .animation-container { text-align: center; }
        .circle-loader { margin: 0 auto 15px; border: 4px solid rgba(255, 255, 255, 0.2); border-left-color: #ffffff; border-radius: 50%; width: 80px; height: 80px; animation: spin 1s linear infinite; }
        .checkmark-loader { border-color: rgba(122, 193, 66, 0.3); border-left-color: #7ac142; }
        .cross-loader { border-color: rgba(244, 67, 54, 0.3); border-left-color: #F44336; }
        .animation-message { font-size: 1.4em; font-weight: bold; color: white; margin-top: 20px; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div class="balance-card">
        <h4>TOTAL BALANCE</h4>
        <h1 class="amount">৳{{ number_format($user->Balance ?? 0, 2) }}</h1>
        <p class="sub-balance">Winning Balance: ৳{{ number_format($user->Winning ?? 0, 2) }}</p>
    </div>

    <div class="actions">
        <button class="action-btn" onclick="openModal('addMoneyModal')"><i class="fas fa-plus-circle" style="color:var(--success)"></i> Add Money</button>
        <button class="action-btn" onclick="openModal('withdrawModal')"><i class="fas fa-arrow-circle-up" style="color:var(--error)"></i> Withdraw</button>
        <button class="action-btn" onclick="openModal('transferModal')"><i class="fas fa-exchange-alt" style="color:#2196F3"></i> Transfer</button>
    </div>

    <div class="history-section">
        <h3>Recent Transactions</h3>
        @forelse ($transactions as $txn)
            <div class="txn-item">
                <div class="txn-details">
                    <p><strong>{{ $txn->type }}</strong> - <small>{{ $txn->Method }}</small></p>
                    <p style="font-size: 0.8em; color: #666;">
                        {{ \Carbon\Carbon::parse($txn->Date)->format('d M Y, h:i A') }}
                    </p>
                </div>
                <div class="txn-status-wrapper">
                    <div class="txn-amount {{ strtolower($txn->type) }}">
                        {{ $txn->type == 'Deposit' ? '+' : '-' }}৳{{ number_format((float)$txn->Amount, 2) }}
                    </div>
                    <div class="txn-status {{ strtolower($txn->Status) }}">
                        {{ $txn->Status }}
                    </div>
                </div>
            </div>
        @empty
            <p style="text-align:center; color:#888;">No transactions yet.</p>
        @endforelse
    </div>
    
    <!-- Modals -->
    <!-- Add Money Modal -->
<div id="addMoneyModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>টাকা যোগ করুন</h2>
      <span class="close" onclick="closeModal('addMoneyModal')">&times;</span>
    </div>

    <form method="POST" action="{{ route('wallet.transaction') }}">
      @csrf
      <input type="hidden" name="action" value="add_money">
      <input type="hidden" id="add_method" name="method" value="bKash">

      <!-- Payment Method Cards -->
      <div class="method-container">
        <div class="method-card active" onclick="selectMethod('bKash', event)">
          <img src="{{ asset('assets/images/b.png') }}" alt="bKash">
          <p>bKash</p>
        </div>
        <div class="method-card" onclick="selectMethod('Rocket', event)">
          <img src="https://upload.wikimedia.org/wikipedia/commons/4/45/Rocket_mobile_banking_logo.svg" alt="Rocket">
          <p>Rocket</p>
        </div>
        <div class="method-card" onclick="selectMethod('Nagad', event)">
          <img src="{{ asset('assets/images/Nagad_Logo.svg') }}" alt="Nagad">
          <p>Nagad</p>
        </div>
      </div>

      <!-- Payment Info Section -->
      <div class="payment-box">
        <h3>ট্রানজেকশন আইডি দিন</h3>
        <div class="steps" id="paymentSteps">
          <!-- Steps will be dynamically filled -->
        </div>

        <div class="form-group">
          <label>Amount (৳)</label>
          <input type="number" name="amount" placeholder="যেমনঃ ৫০" required>
        </div>

        <div class="form-group">
          <label>Transaction ID</label>
          <input type="text" name="transaction_id" placeholder="ট্রানজেকশন আইডি লিখুন" required>
        </div>

        <button type="submit" class="verify-btn">VERIFY</button>
      </div>
    </form>
  </div>
</div>

<!-- CSS (same as your design) -->
<style>
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
  justify-content: center; align-items: center;
}
.modal-content {
  background: #fff;
  border-radius: 16px;
  max-width: 420px;
  width: 90%;
  padding: 20px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.2);
  animation: slideIn 0.3s ease;
}
@keyframes slideIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ff007f; padding-bottom: 8px; margin-bottom: 10px; }
.modal-header h2 { color: #ff007f; font-size: 20px; font-weight: bold; }
.modal-header .close { font-size: 26px; cursor: pointer; color: #444; }

.method-container { display: flex; justify-content: space-between; gap: 10px; margin: 15px 0; }
.method-card { flex: 1; text-align: center; border: 2px solid #eee; border-radius: 12px; padding: 10px; cursor: pointer; background: #fafafa; transition: all 0.3s ease; }
.method-card img { width: 50px; height: 50px; object-fit: contain; }
.method-card p { margin-top: 5px; font-weight: 600; color: #333; }
.method-card.active { border: 2px solid #ff007f; background: #ffe6f3; }

.payment-box { background: #ff007f; color: #fff; padding: 15px; border-radius: 12px; margin-top: 10px; }
.payment-box h3 { text-align: center; font-size: 18px; margin-bottom: 10px; font-weight: bold; }
.payment-box .steps p { font-size: 14px; line-height: 1.5; margin-bottom: 8px; }
.copy-box { background: #fff; color: #333; border-radius: 8px; padding: 6px 10px; display: flex; align-items: center; justify-content: space-between; margin: 8px 0; }
.copy-btn { background: #ff007f; color: #fff; border: none; padding: 4px 10px; border-radius: 6px; cursor: pointer; font-weight: 600; }
.copy-btn:hover { background: #d9006c; }
.form-group { margin-top: 10px; }
.form-group label { display: block; font-weight: bold; font-size: 14px; margin-bottom: 5px; }
.form-group input { width: 100%; border: none; border-radius: 8px; padding: 10px; font-size: 15px; outline: none; }
.verify-btn { width: 100%; background: #fff; color: #ff007f; border: none; font-weight: bold; padding: 12px; border-radius: 8px; margin-top: 15px; font-size: 16px; cursor: pointer; transition: all 0.3s ease; }
.verify-btn:hover { background: #ffe6f3; }
</style>

<!-- JS -->
<script>
const paymentData = {
  bKash: { number: "{{ $adminSettings->{'bKash Number'} ?? '01339201003' }}", dial: '*247#', app: 'bKash', color: '#ff007f' },
  Rocket: { number: "{{ $adminSettings->{'Rocket Number'} ?? '01700123456' }}", dial: '*322#', app: 'Rocket', color: '#8000ff' },
  Nagad: { number: "{{ $adminSettings->{'Nagad Number'} ?? '01800123456' }}", dial: '*167#', app: 'Nagad', color: '#f5c200' }
};

function selectMethod(method, event) {
  document.getElementById('add_method').value = method;

  // Update active class
  document.querySelectorAll('.method-card').forEach(card => card.classList.remove('active'));
  event.currentTarget.classList.add('active');

  // Update payment box content and color
  updatePaymentSteps(method);
}

function updatePaymentSteps(method) {
  const data = paymentData[method];
  const stepsContainer = document.getElementById('paymentSteps');
  const paymentBox = document.querySelector('.payment-box');

  // Change payment box background color
  paymentBox.style.background = data.color;

  // Optional: change text color for contrast if needed
  if (method === 'Nagad') paymentBox.style.color = '#333'; 
  else paymentBox.style.color = '#fff';

  stepsContainer.innerHTML = `
    <p>🔘 ${data.dial} ডায়াল করে আপনার <strong>${data.app}</strong> মোবাইল মেনুতে যান অথবা <strong>${data.app}</strong> অ্যাপে যান।</p>
    <p>🔘 <strong>Send Money</strong> - এ ক্লিক করুন।</p>
    <p>🔘 আপনার আ্যড মানি ৫-১০ মিনিটের মধ্যে সম্পূর্ণ করা হবে।</p>
    <p>🔘 প্রাপক নম্বর হিসেবে নিচের এই নম্বরটি লিখুনঃ</p>
    <div class="copy-box">
      <span id="paymentNumberDisplay">${data.number}</span>
      <button type="button" class="copy-btn" onclick="copyText('paymentNumberDisplay')">Copy</button>
    </div>
    <p>🔘 নিশ্চিত করতে এখন আপনার ${data.app} মোবাইল মেনু পিন লিখুন।</p>
    <p>🔘 এখন উপরের বক্সে আপনার <strong>Transaction ID</strong> এবং <strong>Amount</strong> দিন এবং নিচের Verify বাটনে ক্লিক করুন।</p>
  `;
}

function copyText(elementId) {
  const text = document.getElementById(elementId).innerText;
  navigator.clipboard.writeText(text);
  alert('Number copied: ' + text);
}

function closeModal(id) {
  document.getElementById(id).style.display = 'none';
}

// Initialize modal with bKash info
document.addEventListener('DOMContentLoaded', () => { updatePaymentSteps('bKash'); });
</script>


    <div id="withdrawModal" class="modal"><div class="modal-content"><div class="modal-header"><h2>Withdraw Money</h2><span class="close" onclick="closeModal('withdrawModal')">×</span></div><form method="POST" action="{{ route('wallet.transaction') }}"><input type="hidden" name="action" value="withdraw">@csrf<div class="form-group"><label>Withdraw To</label><select name="method"><option value="bKash">bKash</option><option value="Nagad">Nagad</option><option value="Rocket">Rocket</option></select></div><div class="form-group"><label>Amount</label><input type="number" step="0.01" name="amount" placeholder="Minimum ৳{{ number_format((float)($adminSettings->{'Minimum Withdraw'} ?? 0), 2) }}" max="{{ (float)($user->Winning ?? 0) }}" required></div><div class="form-group"><label>Your Account Number</label><input type="text" name="account_no" placeholder="Enter Bkash/Nagad/Rocket number" required></div><p class="info-msg">Note: You can only withdraw from your <strong>Winning Balance</strong>.আপনার প্রতিটি উইথড্র থেকে ৫ টাকা করে ফি নেওয়া হবে।</p><button type="submit" class="modal-btn">Submit Withdraw</button></form></div></div>
    <div id="transferModal" class="modal"><div class="modal-content"><div class="modal-header"><h2>Transfer to Main Balance</h2><span class="close" onclick="closeModal('transferModal')">×</span></div><form method="POST" action="{{ route('wallet.transaction') }}"><input type="hidden" name="action" value="transfer_balance">@csrf<p style="text-align:center;">Your Winning Balance: <strong>৳{{ number_format($user->Winning ?? 0, 2) }}</strong></p><div class="form-group"><label>Amount to Transfer</label><input type="number" step="0.01" name="amount" placeholder="Enter amount to transfer" required max="{{ (float)($user->Winning ?? 0) }}"></div><p class="info-msg">The amount will be moved from winning balance to main balance.</p><button type="submit" class="modal-btn">Confirm Transfer</button></form></div></div>

    <!-- Status Animation Overlay -->
    @if(session('status_type') && session('status_message'))
    <div id="status-animation" class="animation-overlay" style="display: flex;">
        <div class="animation-container">
            <div id="loader-icon"></div>
            <h2 id="animation-message" class="animation-message"></h2>
        </div>
    </div>
    @endif
    
    @include('partials.bottom-nav')

    <script>
        function openModal(id) { document.getElementById(id).style.display = 'flex'; }
        function closeModal(id) { document.getElementById(id).style.display = 'none'; }
        window.onclick = function(event) { if (event.target.classList.contains('modal')) closeModal(event.target.id); }
        
        const adminSettings = @json($adminSettings ?? []);
        const paymentNumbers = { 
            'bKash': adminSettings['bKash Number'] || 'N/A', 
            'Nagad': adminSettings['Nagad Number'] || 'N/A', 
            'Rocket': adminSettings['Rocket Number'] || 'N/A' 
        };
        function updatePaymentInfo() { 
            const selectedMethod = document.getElementById('add_method').value; 
            document.getElementById('paymentNumberDisplay').innerText = paymentNumbers[selectedMethod] || 'Not Available'; 
        }
        document.addEventListener('DOMContentLoaded', updatePaymentInfo);

        function showStatusAnimation(status, message) {
            const overlay = document.getElementById('status-animation');
            const loaderIcon = document.getElementById('loader-icon');
            const messageEl = document.getElementById('animation-message');
            
            if (!overlay || !loaderIcon || !messageEl) return;

            messageEl.innerText = message;
            if(status === 'success') { 
                loaderIcon.innerHTML = `<div class="circle-loader checkmark-loader"></div>`; 
            } else { 
                loaderIcon.innerHTML = `<div class="circle-loader cross-loader"></div>`; 
            }
            overlay.style.display = 'flex';
            setTimeout(() => { 
                window.location.href = "{{ route('wallet.index') }}";
            }, 2500);
        }

        const formStatus = "{{ session('status_type') }}";
        const statusMessage = "{{ session('status_message') }}";

        if (formStatus && statusMessage) {
            showStatusAnimation(formStatus, statusMessage);
        }
    </script>
</body>
</html>