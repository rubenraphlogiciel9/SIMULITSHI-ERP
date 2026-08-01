</div> <!-- Fin container-fluid -->
</div> <!-- Fin page-content-wrapper -->
</div> <!-- Fin wrapper -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Toggle menu latéral
    document.getElementById('menu-toggle')?.addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('wrapper').classList.toggle('toggled');
    });
</script>
</body>
</html>