<style>
    .status-badge, .priority-badge {
        border-radius: 30px;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .card {
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-2px);
    }
</style>

<div class="modal fade" id="modal-seg-detalle" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white px-2 py-1">
                <h1 class="modal-title fs-6" id="display_title_becado"><i class="bi bi-eye me-2"></i>Detalle del Seguimiento | <span id="fecha_seg"></span></h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <div class="card m-0 p-1"  id="modalContentDetalle">

                </div>
            </div>
        </div>
    </div>
</div>