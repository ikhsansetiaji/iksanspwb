$(document).ready(function () {
    $('#registrationForm').on('submit', function (e) {
        e.preventDefault(); // Cegah form reload

        // Ambil data form
        const formData = $(this).serialize();

        // Kirim data dengan AJAX
        $.ajax({
            url: './user/submit_registration.php', // File PHP untuk menangani submit
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    // Redirect ke halaman pembayaran dengan parameter
                    window.location.href = `./user/payment.php?member_id=${response.member_id}&package_id=${response.package_id}`;
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});
// Navbar scroll effect
document.addEventListener("scroll", () => {
    document.querySelector(".navbar").classList.toggle("scrolled", window.scrollY > 50);
});
document.addEventListener("DOMContentLoaded", () => {
    const carousel = document.querySelector('#testimonial-carousel');
    const bootstrapCarousel = new bootstrap.Carousel(carousel, {
        interval: 5000, // Waktu antar slide (ms)
        wrap: true // Loop kembali ke awal setelah slide terakhir
    });
});

function calculateBMI() {
    const weight = parseFloat(document.getElementById('weight').value);
    const height = parseFloat(document.getElementById('height').value) / 100; // Convert cm to meters

    if (isNaN(weight) || isNaN(height) || weight <= 0 || height <= 0) {
        document.getElementById('bmi-value').textContent = 'Please enter valid weight and height!';
        document.getElementById('bmi-classification').textContent = '';
        return;
    }

    const bmi = (weight / (height * height)).toFixed(1);
    document.getElementById('bmi-value').textContent = bmi;

    let classification = '';
    if (bmi < 18.5) {
        classification = 'Underweight';
    } else if (bmi >= 18.5 && bmi < 24.9) {
        classification = 'Normal weight';
    } else if (bmi >= 25 && bmi < 29.9) {
        classification = 'Overweight';
    } else {
        classification = 'Obesity';
    }

    document.getElementById('bmi-classification').textContent = `Classification: ${classification}`;
}

var trainersCarousel = document.querySelector('#trainers-carousel');
var carousel = new bootstrap.Carousel(trainersCarousel, {
    interval: 3000, // Waktu rotasi (ms), gunakan false untuk mematikan autoplay
    pause: 'hover'
      // Carousel berhenti saat di-hover
});

function openMaps() {
    window.open("https://goo.gl/maps/examplelink", "_blank");
}

   

