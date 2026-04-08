{{--
    Modal Form Component — Digunakan oleh semua section pages
    Di-include langsung dari layouts/app.blade.php (bukan dari section views)
    sehingga selalu berada di root body, tidak terkekang div parent manapun.
--}}
<div
    id="modal-overlay"
    onclick="closeModalOnOverlay(event)"
>
    <div id="modal-box">
        {{-- Modal Header --}}
        <div class="modal-hdr">
            <h3 id="modal-title"></h3>
            <button onclick="closeModal()" class="modal-close-btn" aria-label="Tutup">
                @include('components.icon', ['name' => 'x', 'class' => 'w-5 h-5'])
            </button>
        </div>

        {{-- Modal Body --}}
        <div id="modal-body">
            {{-- JS will inject form content here --}}
        </div>

        {{-- Modal Footer --}}
        <div class="modal-ftr">
            <button onclick="closeModal()" class="modal-btn-cancel">
                TUTUP
            </button>
            <button
                id="modal-save-btn"
                class="modal-btn-save"
                onclick="submitModalForm()"
            >
                SIMPAN PERUBAHAN
            </button>
        </div>
    </div>
</div>
