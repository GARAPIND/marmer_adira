@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #34495e;
        }

        .alamat-card {
            border: 1.5px solid #f0f0f0;
            border-radius: 18px;
            transition: all 0.2s;
        }

        .alamat-card:hover {
            border-color: var(--adira-gold);
            box-shadow: 0 8px 24px rgba(197, 164, 126, 0.12);
        }

        .alamat-card.is-utama {
            border-color: var(--adira-gold);
            background: #fffdf9;
        }

        .badge-utama {
            background: var(--adira-gold);
            color: white;
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .input-ae {
            border: 1.5px solid #f0f0f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            background: #f8f9fa;
            transition: all 0.2s;
            width: 100%;
        }

        .input-ae:focus {
            border-color: var(--adira-gold);
            background: white;
            box-shadow: 0 0 0 3px rgba(197, 164, 126, 0.15);
            outline: none;
        }

        .input-ae:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-gold {
            background: var(--adira-gold);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-gold:hover {
            background: #b8926a;
            color: white;
        }

        .btn-dark-ae {
            background: var(--adira-dark);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
        }

        .empty-state {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
        }
    </style>

    {{-- Meta CSRF untuk fetch --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Daftar Alamat</h4>
                <p class="text-muted small mb-0">Kelola alamat pengiriman Anda</p>
            </div>
            <button class="btn btn-gold px-4" onclick="bukaModalTambah()">
                <i class="fa-solid fa-plus me-2"></i>Tambah Alamat
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success rounded-3">{{ session('success') }}</div>
        @endif

        @if ($alamat->isEmpty())
            <div class="empty-state">
                <i class="fa-solid fa-location-dot fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold text-muted">Belum Ada Alamat</h5>
                <p class="text-muted small">Tambahkan alamat pengiriman Anda untuk mempermudah proses checkout.</p>
                <button class="btn btn-gold mt-2" onclick="bukaModalTambah()">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Alamat Pertama
                </button>
            </div>
        @else
            <div class="row g-3">
                @foreach ($alamat as $a)
                    <div class="col-md-6">
                        <div class="alamat-card p-4 {{ $a->is_utama ? 'is-utama' : '' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold">{{ $a->label }}</span>
                                    @if ($a->is_utama)
                                        <span class="badge-utama">Utama</span>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    @if (!$a->is_utama)
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill"
                                            onclick="setUtama({{ $a->id }})">
                                            <i class="fa-regular fa-star me-1"></i>Utamakan
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-warning rounded-pill"
                                        onclick="bukaModalEdit({{ $a->id }})">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill"
                                        onclick="hapusAlamat({{ $a->id }})">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="mb-1 fw-semibold">{{ $a->nama_penerima }} &middot; {{ $a->no_telepon }}</p>
                            <p class="mb-0 text-muted small">{{ $a->alamat_lengkap }}, {{ $a->kecamatan_nama }},
                                {{ $a->kota_nama }}, {{ $a->provinsi_nama }} {{ $a->kode_pos }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="modalAlamat" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalAlamatTitle">Tambah Alamat Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Label Alamat</label>
                            <input type="text" id="f_label" class="input-ae" placeholder="Rumah, Kantor, dll">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nama Penerima</label>
                            <input type="text" id="f_nama_penerima" class="input-ae">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">No. Telepon</label>
                            <input type="text" id="f_no_telepon" class="input-ae">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kode Pos</label>
                            <input type="text" id="f_kode_pos" class="input-ae">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Provinsi</label>
                            <select id="f_provinsi" class="input-ae" onchange="onProvinsiChange()">
                                <option value="">-- Pilih --</option>
                            </select>
                            <input type="hidden" id="f_provinsi_nama">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Kota / Kabupaten</label>
                            <select id="f_kota" class="input-ae" disabled onchange="onKotaChange()">
                                <option value="">-- Pilih --</option>
                            </select>
                            <input type="hidden" id="f_kota_nama">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Kecamatan</label>
                            <select id="f_kecamatan" class="input-ae" disabled onchange="onKecamatanChange()">
                                <option value="">-- Pilih --</option>
                            </select>
                            <input type="hidden" id="f_kecamatan_nama">
                            <input type="hidden" id="f_kecamatan_id_val">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Alamat Lengkap</label>
                            <textarea id="f_alamat_lengkap" class="input-ae" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="f_is_utama">
                                <label class="form-check-label small fw-semibold" for="f_is_utama">Jadikan alamat
                                    utama</label>
                            </div>
                        </div>
                    </div>
                    <div id="alert_form_alamat" class="alert alert-danger mt-3 d-none"></div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-dark-ae px-4 rounded-3" id="btn_simpan"
                        onclick="simpanAlamat()">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ── CSRF helper ───────────────────────────────────────────────────────────────
        function getCsrf() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        async function apiFetch(url, method = 'GET', body = null) {
            const opts = {
                method,
                headers: {
                    'X-CSRF-TOKEN': getCsrf(),
                    'Accept': 'application/json',
                },
            };

            if (body) {
                opts.headers['Content-Type'] = 'application/json';
                opts.body = JSON.stringify(body);
            }

            const res = await fetch(url, opts);
            const json = await res.json();

            if (res.status === 419) {
                alert('Sesi habis, halaman akan dimuat ulang.');
                location.reload();
                return null;
            }

            return json;
        }

        // ── Dropdown helpers ──────────────────────────────────────────────────────────
        let provinsiLoaded = false;

        function resetSelect(id, disable = true) {
            const el = document.getElementById(id);
            el.innerHTML = '<option value="">-- Pilih --</option>';
            el.disabled = disable;
        }

        async function loadProvinsi() {
            if (provinsiLoaded) return;
            const json = await apiFetch('/ongkir/provinsi');
            if (!json) return;
            const list = json.data || [];
            const sel = document.getElementById('f_provinsi');
            list.forEach(p => {
                const opt = new Option(p.name, p.id);
                sel.appendChild(opt);
            });
            provinsiLoaded = true;
        }

        async function onProvinsiChange() {
            const sel = document.getElementById('f_provinsi');
            const provId = sel.value;
            if (!provId) return;

            document.getElementById('f_provinsi_nama').value = sel.options[sel.selectedIndex].text;
            resetSelect('f_kota', true);
            resetSelect('f_kecamatan', true);
            document.getElementById('f_kota_nama').value = '';
            document.getElementById('f_kecamatan_nama').value = '';
            document.getElementById('f_kecamatan_id_val').value = '';

            const json = await apiFetch('/ongkir/kota?province_id=' + provId);
            if (!json) return;
            const list = json.data || [];
            const kotaSel = document.getElementById('f_kota');
            list.forEach(k => {
                const label = k.name;
                const opt = new Option(label, k.id);
                opt.dataset.nama = label;
                kotaSel.appendChild(opt);
            });
            kotaSel.disabled = false;
        }

        async function onKotaChange() {
            const sel = document.getElementById('f_kota');
            const kotaId = sel.value;
            if (!kotaId) return;

            const opt = sel.options[sel.selectedIndex];
            document.getElementById('f_kota_nama').value = opt.dataset.nama || opt.text;
            resetSelect('f_kecamatan', true);
            document.getElementById('f_kecamatan_nama').value = '';
            document.getElementById('f_kecamatan_id_val').value = '';

            const json = await apiFetch('/ongkir/kecamatan?city_id=' + kotaId);
            if (!json) return;
            const list = json.data || [];
            const kecSel = document.getElementById('f_kecamatan');
            list.forEach(k => {
                const opt = new Option(k.name, k.id);
                opt.dataset.nama = k.name;
                kecSel.appendChild(opt);
            });
            kecSel.disabled = false;
        }

        function onKecamatanChange() {
            const sel = document.getElementById('f_kecamatan');
            const opt = sel.options[sel.selectedIndex];
            document.getElementById('f_kecamatan_nama').value = opt.dataset.nama || opt.text;
            document.getElementById('f_kecamatan_id_val').value = sel.value;
        }

        // ── Modal ─────────────────────────────────────────────────────────────────────
        function resetForm() {
            ['f_label', 'f_nama_penerima', 'f_no_telepon', 'f_kode_pos', 'f_alamat_lengkap',
                'f_provinsi_nama', 'f_kota_nama', 'f_kecamatan_nama', 'f_kecamatan_id_val'
            ].forEach(id => {
                document.getElementById(id).value = '';
            });
            document.getElementById('f_is_utama').checked = false;
            document.getElementById('f_provinsi').value = '';
            resetSelect('f_kota', true);
            resetSelect('f_kecamatan', true);
            document.getElementById('alert_form_alamat').classList.add('d-none');
            document.getElementById('alamat_id_edit_val').value = '';
        }

        function bukaModalTambah() {
            document.getElementById('modalAlamatTitle').innerText = 'Tambah Alamat Baru';
            resetForm();
            loadProvinsi();
            new bootstrap.Modal(document.getElementById('modalAlamat')).show();
        }

        async function bukaModalEdit(id) {
            document.getElementById('modalAlamatTitle').innerText = 'Edit Alamat';
            resetForm();
            await loadProvinsi();

            const list = await apiFetch('/alamat/list');
            if (!list) return;
            const a = list.find(x => x.id === id);
            if (!a) return;

            document.getElementById('alamat_id_edit_val').value = id;
            document.getElementById('f_label').value = a.label;
            document.getElementById('f_nama_penerima').value = a.nama_penerima;
            document.getElementById('f_no_telepon').value = a.no_telepon;
            document.getElementById('f_kode_pos').value = a.kode_pos || '';
            document.getElementById('f_alamat_lengkap').value = a.alamat_lengkap;
            document.getElementById('f_is_utama').checked = !!a.is_utama;
            document.getElementById('f_provinsi_nama').value = a.provinsi_nama;
            document.getElementById('f_kota_nama').value = a.kota_nama;
            document.getElementById('f_kecamatan_nama').value = a.kecamatan_nama;
            document.getElementById('f_kecamatan_id_val').value = a.kecamatan_id;

            document.getElementById('f_provinsi').value = a.provinsi_id;
            await onProvinsiChange();

            document.getElementById('f_kota').value = a.kota_id;
            document.getElementById('f_kota_nama').value = a.kota_nama;
            await onKotaChange();

            document.getElementById('f_kecamatan').value = a.kecamatan_id;
            document.getElementById('f_kecamatan_nama').value = a.kecamatan_nama;
            document.getElementById('f_kecamatan_id_val').value = a.kecamatan_id;

            new bootstrap.Modal(document.getElementById('modalAlamat')).show();
        }

        async function simpanAlamat() {
            const alertEl = document.getElementById('alert_form_alamat');
            alertEl.classList.add('d-none');

            const id = document.getElementById('alamat_id_edit_val').value;

            const body = {
                label: document.getElementById('f_label').value,
                nama_penerima: document.getElementById('f_nama_penerima').value,
                no_telepon: document.getElementById('f_no_telepon').value,
                alamat_lengkap: document.getElementById('f_alamat_lengkap').value,
                provinsi_id: document.getElementById('f_provinsi').value,
                provinsi_nama: document.getElementById('f_provinsi_nama').value,
                kota_id: document.getElementById('f_kota').value,
                kota_nama: document.getElementById('f_kota_nama').value,
                kecamatan_id: document.getElementById('f_kecamatan_id_val').value,
                kecamatan_nama: document.getElementById('f_kecamatan_nama').value,
                kode_pos: document.getElementById('f_kode_pos').value,
                is_utama: document.getElementById('f_is_utama').checked ? '1' : '0',
            };

            const url = id ? '/alamat/' + id : '/alamat';
            const method = id ? 'PUT' : 'POST';

            const btn = document.getElementById('btn_simpan');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Menyimpan...';

            const json = await apiFetch(url, method, body);

            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i>Simpan';

            if (!json) return;

            if (json.success) {
                location.reload();
            } else {
                const msgs = json.errors ?
                    Object.values(json.errors).flat().join('<br>') :
                    (json.message || 'Terjadi kesalahan.');
                alertEl.innerHTML = msgs;
                alertEl.classList.remove('d-none');
            }
        }

        async function hapusAlamat(id) {
            if (!confirm('Hapus alamat ini?')) return;
            const json = await apiFetch('/alamat/' + id, 'DELETE');
            if (json) location.reload();
        }

        async function setUtama(id) {
            const json = await apiFetch('/alamat/' + id + '/utama', 'PATCH');
            if (json) location.reload();
        }
    </script>

    {{-- Hidden field untuk ID edit, di luar modal agar tidak ter-reset form --}}
    <input type="hidden" id="alamat_id_edit_val" value="">

@endsection
