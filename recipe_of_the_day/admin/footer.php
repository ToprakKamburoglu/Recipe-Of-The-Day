        </div> 
        <footer class="text-center py-3">
            <div class="container-fluid">
                <p class="mb-0 text-muted small fw-bold">
                    Admin Panel © <?= date('Y') ?> | All Rights Reserved.
                </p>
            </div>
        </footer>

    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");
        
        if (!el || !toggleButton) return;
        if (localStorage.getItem('sidebarToggled') === 'true') {
            el.classList.add('toggled');
        }
        
        toggleButton.onclick = function (e) {
            e.preventDefault();
            e.stopPropagation();
            
            el.classList.toggle("toggled");
            
            if (el.classList.contains('toggled')) {
                localStorage.setItem('sidebarToggled', 'true');
            } else {
                localStorage.setItem('sidebarToggled', 'false');
            }
        };
        
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 768) {
                var clickedToggle = e.target.closest('#menu-toggle');
                var clickedSidebar = e.target.closest('.sidebar-wrapper');
                
                if (!clickedToggle && !clickedSidebar && !el.classList.contains('toggled')) {
                    el.classList.add('toggled');
                    localStorage.setItem('sidebarToggled', 'true');
                }
            }
        });
    });
        
    </script>
    </body>
</html>