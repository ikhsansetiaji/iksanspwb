<div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update-member-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMemberModalLabel">Edit Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Hidden field for Member ID -->
                    <input type="hidden" id="edit-member-id" name="id">
                    
                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="edit-member-nama" class="form-label">Nama Member</label>
                        <input type="text" class="form-control" id="edit-member-nama" name="nama" required>
                    </div>
                    
                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="edit-member-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit-member-email" name="email" required>
                    </div>
                    
                    <!-- Phone Number Field -->
                    <div class="mb-3">
                        <label for="edit-member-telepon" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="edit-member-telepon" name="telepon" required>
                    </div>
                    
                    <!-- Subscription Status -->
                    <div class="mb-3">
                        <label for="edit-member-status" class="form-label">Status Keanggotaan</label>
                        <select class="form-select" id="edit-member-status" name="status" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Member</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('update-member-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission
        const formData = new FormData(this);
    
        fetch('update_member.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Member berhasil diperbarui!');
                    location.reload(); // Reload the page or update the table dynamically
                } else {
                    alert('Gagal memperbarui member: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
    
</script>

