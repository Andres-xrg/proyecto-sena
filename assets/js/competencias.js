 function toggleCard(cardId) {
            const content = document.getElementById('content-' + cardId);
            const chevron = document.getElementById('chevron-' + cardId);
            
            if (content.classList.contains('open')) {
                content.classList.remove('open');
                chevron.classList.remove('rotated');
            } else {
                content.classList.add('open');
                chevron.classList.add('rotated');
            }
        }

        // Initialize some sections as open
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('chevron-competencias').classList.add('rotated');
            document.getElementById('chevron-transversales').classList.add('rotated');
        });