<!-- resources/views/partials/wallet-modals.blade.php -->

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

            <!-- Payment Method Section -->
            <div class="method-buttons">
                <div class="method" onclick="selectMethod('bKash')">
                    <img src="/images/bkash.png" alt="bKash">
                    <span>bKash</span>
                </div>
                <div class="method" onclick="selectMethod('Rocket')">
                    <img src="/images/rocket.png" alt="Rocket">
                    <span>Rocket</span>
                </div>
                <div class="method" onclick="selectMethod('Nagad')">
                    <img src="/images/nagad.png" alt="Nagad">
                    <span>Nagad</span>
                </div>
            </div>

            <input type="hidden" id="add_method" name="method" value="bKash">

            <!-- Instruction Box -->
            <div class="instruction-box">
                <h3>ট্রানজেকশন আইডি দিন</h3>
                <p>📱 *247# ডায়াল করে অথবা bKash অ্যাপ ব্যবহার করুন।</p>
                <p><b>Send Money</b> - এ ক্লিক করুন।</p>
                <p>প্রাপক নম্বর: <b id="paymentNumber">01339201003</b> 
                    <button type="button" class="copy-btn" onclick="copyNumber()">Copy</button>
                </p>
                <p>পিন দিয়ে ট্রানজেকশন সম্পূর্ণ করুন।</p>
                <p>এরপর নিচে ট্রানজেকশন আইডি ও পরিমাণ লিখে VERIFY ক্লিক করুন।</p>
            </div>

            <!-- Input Fields -->
            <div class="form-group">
                <label>Transaction ID</label>
                <input type="text" name="trx_id" required placeholder="Enter Transaction ID">
            </div>

            <div class="form-group">
                <label>Amount (৳)</label>
                <input type="number" name="amount" required placeholder="Enter Amount">
            </div>

            <button type="submit" class="verify-btn">VERIFY</button>
        </form>
    </div>
</div>

<!-- CSS -->
<style>
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.6);
  justify-content: center; align-items: center;
}

.modal-content {
  background: #fff;
  border-radius: 20px;
  width: 90%;
  max-width: 400px;
  padding: 20px;
  animation: fadeIn 0.3s ease;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #E50087;
  color: #fff;
  padding: 10px 15px;
  border-radius: 15px 15px 0 0;
}

.close {
  font-size: 22px;
  cursor: pointer;
  color: white;
}

.method-buttons {
  display: flex;
  justify-content: space-between;
  margin: 20px 0;
}

.method {
  flex: 1;
  text-align: center;
  margin: 0 5px;
  padding: 10px;
  background: #f7f2f7;
  border: 2px solid transparent;
  border-radius: 10px;
  cursor: pointer;
  transition: 0.2s;
}

.method img {
  width: 50px;
  height: 50px;
  object-fit: contain;
}

.method.active {
  border-color: #E50087;
  background: #ffe0f1;
}

.instruction-box {
  background: #E50087;
  color: white;
  border-radius: 12px;
  padding: 15px;
  margin-bottom: 20px;
}

.copy-btn {
  background: #555;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 4px 8px;
  margin-left: 8px;
  cursor: pointer;
  font-size: 13px;
}

.verify-btn {
  background: #E50087;
  color: white;
  border: none;
  width: 100%;
  padding: 12px;
  font-size: 16px;
  font-weight: 600;
  border-radius: 10px;
  cursor: pointer;
  transition: 0.2s;
}

.verify-btn:hover {
  background: #d10078;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
</style>

<!-- JS -->
<script>
function closeModal(id) {
  document.getElementById(id).style.display = 'none';
}
function openModal(id) {
  document.getElementById(id).style.display = 'flex';
}
function selectMethod(method) {
  document.querySelectorAll('.method').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.method').forEach(el => {
    if (el.innerText.trim() === method) el.classList.add('active');
  });
  document.getElementById('add_method').value = method;
}
function copyNumber() {
  const num = document.getElementById('paymentNumber').innerText;
  navigator.clipboard.writeText(num);
  alert('Number copied: ' + num);
}
</script>
