@if ($paginator->hasPages())
    <nav aria-label="Page navigation" class="pagination-container">
        <ul class="pagination justify-content-center">
            {{-- Aller à la première page --}}
            <li class="page-item {{ $paginator->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="First">
                    <span aria-hidden="true">&#171;</span>
                </a>
            </li>

            {{-- Bouton Précédent --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&#8249;</span>
                </a>
            </li>

            {{-- Numéro de page actuelle --}}
            <li class="page-item active">
                <span class="page-link">
                    {{ $paginator->currentPage() }}
                </span>
            </li>

            {{-- Bouton Suivant --}}
            <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&#8250;</span>
                </a>
            </li>

            {{-- Aller à la dernière page --}}
            <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Last">
                    <span aria-hidden="true">&#187;</span>
                </a>
            </li>
        </ul>
    </nav>
@endif
