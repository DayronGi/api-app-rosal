<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Filtrar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('assignedtasks.search') }}">
                    @csrf

                    <div class="d-inline-flex flex-row gap-2">
                        <div class="input-group my-2">
                            <label class="input-group-text"><i class="fas fa-calendar"></i></label>
                            <input class="form-control" type="date" name="date_ini" value="{{ session('dateIni') }}" />
                        </div>
                    </div>

                    <div class="d-inline-flex flex-row gap-2">
                        <div class="input-group my-2">
                            <label class="input-group-text"><i class="fas fa-calendar"></i></label>
                            <input class="form-control" type="date" name="date_end" value="{{ session('dateEnd') }}" />
                        </div>
                    </div>

                    <div class="d-inline-flex flex-row gap-2">
                        <div class="input-group my-2">
                            <label class="input-group-text"><i class="fas fa-user"></i></label>
                            <input class="form-control" type="text" name="keyword" value="{{ session('keyword') }}" placeholder="Nombre/Documento" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="action" value="SEARCH"><i class="fas fa-search"></i> BUSCAR</button>
                        <button type="submit" class="btn btn-danger" name="action" value="CLEAR"><i class="fas fa-delete-left"></i> LIMPIAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>