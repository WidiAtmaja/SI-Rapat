import SignaturePad from "signature_pad";

document.addEventListener("alpine:init", () => {
    Alpine.data("absensiForm", (initialData) => ({
        // State Kehadiran
        kehadiran: initialData.kehadiran || "tidak hadir",

        // State Tanda Tangan
        signaturePad: null,
        canvas: null,
        hasOldSignature: initialData.hasOldSignature || false,
        showCanvas: initialData.showCanvas || false,
        isCanvasEmpty: true,

        // State Foto Wajah (Webcam & Gallery)
        photoPreview: initialData.photoPreview || "",
        fileNamePhoto: "",
        activeInput: "none", // 'camera' atau 'file'
        streamActive: false,
        videoStream: null,

        // State Foto Zoom
        zoomPreview: initialData.zoomPreview || "",
        fileNameZoom: "",

        init() {
            // Watcher untuk inisialisasi Signature Pad saat status 'hadir'
            this.$watch("kehadiran", (val) => {
                if (
                    val === "hadir" &&
                    (this.showCanvas || !this.hasOldSignature)
                ) {
                    this.waitForVisibilityAndInit();
                } else {
                    this.stopWebcam(); // Stop kamera jika user ganti status
                }
            });

            this.$watch("showCanvas", (val) => {
                if (val && this.kehadiran === "hadir") {
                    this.waitForVisibilityAndInit();
                }
            });

            // Inisialisasi awal jika status sudah hadir
            if (
                this.kehadiran === "hadir" &&
                (this.showCanvas || !this.hasOldSignature)
            ) {
                this.waitForVisibilityAndInit();
            }
        },

        // --- LOGIKA WEBCAM (UNTUK LAPTOP) ---
        async startWebcam() {
            this.resetPhoto();
            try {
                this.videoStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        facingMode: "user",
                    },
                    audio: false,
                });

                if (this.$refs.video) {
                    this.$refs.video.srcObject = this.videoStream;
                    this.streamActive = true;
                    this.activeInput = "camera";
                }
            } catch (err) {
                console.error("Error webcam:", err);
                alert(
                    "Tidak dapat mengakses kamera. Pastikan izin kamera diizinkan atau gunakan fitur 'Pilih Gallery'."
                );
            }
        },

        takeSnapshot() {
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;
            const context = canvas.getContext("2d");

            // Tangkap gambar sesuai resolusi video asli
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Simpan hasil ke preview (Base64)
            this.photoPreview = canvas.toDataURL("image/jpeg", 0.8);
            this.fileNamePhoto = `selfie_${new Date().getTime()}.jpg`;

            this.stopWebcam();
        },

        stopWebcam() {
            if (this.videoStream) {
                this.videoStream.getTracks().forEach((track) => track.stop());
                this.videoStream = null;
            }
            this.streamActive = false;
        },

        // --- LOGIKA FILE (GALLERY) ---
        handleFile(e, type, category) {
            const file = e.target.files[0];
            if (!file) return;

            const previewUrl = URL.createObjectURL(file);

            if (category === "foto_wajah") {
                this.stopWebcam();
                this.photoPreview = previewUrl;
                this.fileNamePhoto = file.name;
                this.activeInput = "file";
            } else if (category === "foto_zoom") {
                this.zoomPreview = previewUrl;
                this.fileNameZoom = file.name;
            }
        },

        resetPhoto() {
            this.stopWebcam();
            this.photoPreview = "";
            this.fileNamePhoto = "";
            this.activeInput = "none";
            if (this.$refs.fileInput) this.$refs.fileInput.value = "";
        },

        // --- LOGIKA TANDA TANGAN ---
        waitForVisibilityAndInit() {
            // Delay sedikit untuk memastikan modal/elemen sudah ter-render di DOM
            setTimeout(() => {
                const canvasEl = this.$refs.signatureCanvas;
                if (canvasEl && canvasEl.offsetWidth > 0) {
                    this.initPad();
                }
            }, 300);
        },

        initPad() {
            this.canvas = this.$refs.signatureCanvas;
            if (!this.canvas) return;

            if (this.signaturePad) {
                this.resizeCanvas();
                return;
            }

            this.signaturePad = new SignaturePad(this.canvas, {
                backgroundColor: "rgb(255, 255, 255)",
                penColor: "rgb(0, 0, 0)",
            });

            this.signaturePad.addEventListener("beginStroke", () => {
                this.isCanvasEmpty = false;
            });

            this.resizeCanvas();
            window.addEventListener("resize", () => this.resizeCanvas());
        },

        resizeCanvas() {
            if (!this.canvas || !this.signaturePad) return;

            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const data = this.signaturePad.toData(); // Simpan coretan sebelum resize

            this.canvas.width = this.canvas.offsetWidth * ratio;
            this.canvas.height = this.canvas.offsetHeight * ratio;
            this.canvas.getContext("2d").scale(ratio, ratio);

            this.signaturePad.clear();
            this.signaturePad.fromData(data); // Render ulang coretan
        },

        clearSignature() {
            if (this.signaturePad) {
                this.signaturePad.clear();
                this.isCanvasEmpty = true;
            }
        },

        // --- LOGIKA SUBMIT ---
        async prepareSubmission(e) {
            const form = e.target;
            const tteInput = form.querySelector("input[name=tanda_tangan]");

            if (this.kehadiran === "hadir") {
                // 1. Validasi Foto Wajah
                if (!this.photoPreview) {
                    alert("Mohon ambil foto wajah atau pilih dari gallery.");
                    return;
                }

                if (
                    this.activeInput === "camera" &&
                    this.photoPreview.startsWith("data:image")
                ) {
                    const response = await fetch(this.photoPreview);
                    const blob = await response.blob();
                    const file = new File([blob], "selfie_absensi.jpg", {
                        type: "image/jpeg",
                    });

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    this.$refs.fileInput.files = dataTransfer.files;
                }

                // 3. Handle Tanda Tangan
                if (this.showCanvas || !this.hasOldSignature) {
                    if (this.signaturePad && !this.signaturePad.isEmpty()) {
                        tteInput.value =
                            this.signaturePad.toDataURL("image/png");
                    } else if (!this.hasOldSignature) {
                        alert("Silakan isi tanda tangan terlebih dahulu.");
                        return;
                    }
                }
            }

            // Submit Form Secara Manual
            this.stopWebcam();
            form.submit();
        },
    }));
});
