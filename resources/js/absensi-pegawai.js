import SignaturePad from 'signature_pad';

document.addEventListener('alpine:init', () => {
    Alpine.data('absensiForm', (initialData) => ({
        kehadiran: initialData.kehadiran || 'tidak hadir',
        signaturePad: null,
        canvas: null,
        hasOldSignature: initialData.hasOldSignature || false,
        showCanvas: initialData.showCanvas || false,
        photoPreview: initialData.photoPreview || '',
        zoomPreview: initialData.zoomPreview || '',
        fileNamePhoto: '',
        fileNameZoom: '',
        activeInput: 'none',
        isCanvasEmpty: true,

        init() {
            this.$watch('kehadiran', (val) => {
                if (val === 'hadir' && this.showCanvas) {
                    this.waitForVisibilityAndInit();
                }
            });

            this.$watch('showCanvas', (val) => {
                if (val && this.kehadiran === 'hadir') {
                    this.waitForVisibilityAndInit();
                }
            });

            if (this.kehadiran === 'hadir' && this.showCanvas) {
                this.waitForVisibilityAndInit();
            }
        },

        handleFile(e, type, category) {
            const file = e.target.files[0];
            if (file) {
                const previewUrl = URL.createObjectURL(file);
                if (category === 'foto_wajah') {
                    this.fileNamePhoto = file.name;
                    this.photoPreview = previewUrl;
                    this.activeInput = type;
                    if (type === 'camera') this.$refs.fileInput.value = '';
                    else this.$refs.camInput.value = '';
                } else if (category === 'foto_zoom') {
                    this.fileNameZoom = file.name;
                    this.zoomPreview = previewUrl;
                }
            }
        },

        waitForVisibilityAndInit() {
            let attempts = 0;
            const checkInterval = setInterval(() => {
                const canvasEl = this.$refs.signatureCanvas;
                if (canvasEl && canvasEl.offsetWidth > 0) {
                    this.initPad();
                    clearInterval(checkInterval);
                }
                attempts++;
                if (attempts > 20) clearInterval(checkInterval);
            }, 100);
        },

        initPad() {
            if (this.signaturePad) {
                this.resizeCanvas();
                return;
            }

            this.canvas = this.$refs.signatureCanvas;
            if (this.canvas) {
                this.signaturePad = new SignaturePad(this.canvas, {
                    backgroundColor: 'rgb(255, 255, 255)',
                    penColor: 'rgb(0, 0, 0)'
                });

                this.signaturePad.addEventListener('beginStroke', () => {
                    this.isCanvasEmpty = false;
                });

                this.resizeCanvas();
                window.addEventListener('resize', () => this.resizeCanvas());
            }
        },

        resizeCanvas() {
            if (!this.canvas || !this.signaturePad) return;
            if (this.canvas.offsetWidth === 0) return;

            const data = this.signaturePad.toData();
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            
            this.canvas.width = this.canvas.offsetWidth * ratio;
            this.canvas.height = this.canvas.offsetHeight * ratio;
            this.canvas.getContext('2d').scale(ratio, ratio);

            this.signaturePad.fromData(data);
        },

        clearSignature() {
            if (this.signaturePad) {
                this.signaturePad.clear();
                this.isCanvasEmpty = true;
            }
        },

        prepareSubmission() {
            const form = this.$el;
            const tteInput = form.querySelector('input[name=tanda_tangan]');

            if (this.kehadiran === 'hadir') {
                if (this.showCanvas && this.signaturePad && !this.signaturePad.isEmpty()) {
                    tteInput.value = this.signaturePad.toDataURL('image/png');
                    tteInput.disabled = false;
                } else {
                    tteInput.value = '';
                    tteInput.disabled = true; 
                }
            } else {
                tteInput.value = '';
                tteInput.disabled = false;
            }

            form.submit();
        }
    }));
});