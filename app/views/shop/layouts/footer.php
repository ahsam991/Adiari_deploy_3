    </div> <!-- End container-fluid -->
    
    <!-- Footer -->
    <footer class="footer mt-auto py-4">
        <div class="container text-center">
            <h6 class="fw-bold mb-2 text-primary"><?php echo htmlspecialchars($APP_NAME ?? 'ADI ARI FRESH'); ?></h6>
            <p class="text-muted small mb-2">
                <i class="bi bi-geo-alt"></i> 114-0013 Higashitabata 2-3-1 Osu building 101, Kita City<br>
                <i class="bi bi-telephone"></i> 080-3408-8044
            </p>
            <div class="text-muted small">
                &copy; <?php echo date('Y'); ?> All rights reserved.
            </div>
        </div>
    </footer>
    
    <!-- Scanner Modal (Global) -->
    <div class="modal fade" id="globalScannerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-camera"></i> Scan Barcode</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="global-reader" class="scanner-container"></div>
                    <p class="text-center mt-3 text-muted border-top pt-2">Position the barcode within the box to scan</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Shop Custom JavaScript -->
    <script src="<?php echo Router::url('/js/shop-script.js'); ?>"></script>
    
    <!-- Global Scanner Logic -->
    <script>
        let globalHtml5QrCode;
        let targetInputId;

        function startGlobalScanner(inputId) {
            targetInputId = inputId;
            const modal = new bootstrap.Modal(document.getElementById('globalScannerModal'));
            modal.show();
        }

        document.getElementById('globalScannerModal').addEventListener('shown.bs.modal', function () {
            globalHtml5QrCode = new Html5Qrcode("global-reader");
            const config = { fps: 10, qrbox: { width: 250, height: 150 } };

            globalHtml5QrCode.start(
                { facingMode: "environment" }, 
                config,
                (decodedText, decodedResult) => {
                    const input = document.getElementById(targetInputId);
                    if (input) {
                        input.value = decodedText;
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        if (targetInputId === 'barcodeInput') {
                            const event = new KeyboardEvent('keypress', { key: 'Enter', code: 'Enter', keyCode: 13, which: 13, bubbles: true });
                            input.dispatchEvent(event);
                        }
                    }
                    bootstrap.Modal.getInstance(document.getElementById('globalScannerModal')).hide();
                }
            ).catch(err => console.error("Scanner start error:", err));
        });

        document.getElementById('globalScannerModal').addEventListener('hidden.bs.modal', function () {
            if (globalHtml5QrCode) {
                globalHtml5QrCode.stop().catch(err => console.error("Scanner stop error:", err));
            }
        });

        // Auto dismiss alerts
        setTimeout(function() {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                try {
                    let bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                } catch(e) {}
            });
        }, 5000);
    </script>
</body>
</html>
