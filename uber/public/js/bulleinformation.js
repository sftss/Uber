function toggleInfo(element) {
    // Ferme toutes les bulles actives
    document.querySelectorAll('.info-bubble.active').forEach(bubble => {
        if (bubble !== element) {
            bubble.classList.remove('active');
        }
    });
    
    // Toggle la bulle cliquée
    element.classList.toggle('active');
    
    // Empêche la propagation du clic
    event.stopPropagation();
}

// Ferme la bulle si on clique en dehors
document.addEventListener('click', function(event) {
    if (!event.target.closest('.info-bubble')) {
        document.querySelectorAll('.info-bubble.active').forEach(bubble => {
            bubble.classList.remove('active');
        });
    }
});