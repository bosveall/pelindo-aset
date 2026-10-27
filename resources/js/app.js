import './bootstrap';
import { BrowserMultiFormatReader, BrowserQRCodeSvgWriter } from '@zxing/browser';

const qrCodeWriter = new BrowserQRCodeSvgWriter();

document.querySelectorAll('[data-qr-code]').forEach((element) => {
	try {
		qrCodeWriter.writeToDom(element, element.dataset.qrCode, 240, 240);
		const svg = element.querySelector('svg');
		svg.classList.add('h-full', 'w-full');
	} catch (error) {
		element.textContent = 'QR tidak dapat dibuat.';
		console.error('Gagal membuat QR ruangan.', error);
	}
});

const scannerVideo = document.getElementById('barcode-video');

if (scannerVideo) {
	const startButton = document.getElementById('start-scanner');
	const status = document.getElementById('scanner-status');
	const placeholder = document.getElementById('scanner-placeholder');
	const frame = document.getElementById('scanner-frame');
	const reader = new BrowserMultiFormatReader();
	let controls;
	let scanning = false;

	const stopScanner = () => {
		scanning = false;
		controls?.stop();
		controls = undefined;
		scannerVideo.pause();
		scannerVideo.srcObject = null;
		frame.classList.add('opacity-0');
		placeholder.classList.remove('hidden');
		startButton.textContent = 'Mulai scan';
	};

	const openScanResult = (rawValue) => {
		const value = rawValue.trim();
		let scannedUrl;

		try {
			scannedUrl = new URL(value, window.location.origin);
		} catch {
			scannedUrl = null;
		}

		if (scannedUrl?.origin === window.location.origin
			&& (/^\/ruangan\//.test(scannedUrl.pathname) || /^\/asets\/\d+$/.test(scannedUrl.pathname))) {
			window.location.assign(scannedUrl.href);
			return;
		}

		const lookupUrl = new URL(scannerVideo.dataset.lookupUrl, window.location.origin);
		lookupUrl.searchParams.set('kode', value);
		window.location.assign(lookupUrl.href);
	};

	const startScanner = async () => {
		if (!navigator.mediaDevices?.getUserMedia) {
			status.textContent = 'Browser tidak dapat mengakses kamera. Buka aplikasi melalui HTTPS atau localhost.';
			return;
		}

		try {
			scanning = true;
			placeholder.classList.add('hidden');
			frame.classList.remove('opacity-0');
			startButton.textContent = 'Hentikan scan';
			status.textContent = 'Meminta izin kamera...';
			controls = await reader.decodeFromVideoDevice(undefined, scannerVideo, (result) => {
				if (!result || !scanning) return;

				const value = result.getText();
				stopScanner();
				status.textContent = `Kode ditemukan: ${value}`;
				openScanResult(value);
			});
			status.textContent = 'Kamera aktif. Arahkan ke barcode aset atau QR ruangan.';
		} catch {
			stopScanner();
			status.textContent = 'Kamera tidak dapat digunakan. Izinkan akses kamera dan gunakan koneksi HTTPS.';
		}
	};

	startButton.addEventListener('click', () => {
		if (scanning) {
			stopScanner();
			status.textContent = 'Scanner dihentikan.';
			return;
		}

		startScanner();
	});

	window.addEventListener('pagehide', stopScanner);
}
