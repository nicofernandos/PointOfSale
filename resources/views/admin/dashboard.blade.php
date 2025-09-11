@extends('layouts.admintemplate')

@section('title', 'Dashboard - Point Of Sale')

@section('content')
<div class="row">

    {{-- Dashboard untuk Admin --}}
    @if (Auth::user()->role == 'Admin')

      <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
              <div class="card-body">
                  <div class="card-title d-flex align-items-start justify-content-between">
                      <div class="avatar flex-shrink-0">
                          <i class="bx bxs-group text-primary" style="font-size: 2.2rem;"></i>
                      </div>
                  </div>
                  <span class="fw-semibold d-block mb-1">Pelanggan</span>
                  <h3 class="card-title mb-2"><?= 10 ?></h3>
              </div>
          </div>
      </div>
      <!-- Card Layanan Tambahan -->
      <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
              <div class="card-body">
                  <div class="card-title d-flex align-items-start justify-content-between">
                      <div class="avatar flex-shrink-0">
                          <i class="bx bxs-spa text-info" style="font-size: 2.2rem;"></i>
                      </div>
                  </div>
                  <span class="fw-semibold d-block mb-1">Total Barang</span>
                  <h3 class="card-title text-nowrap mb-2"><?= 20 ?></h3>
              </div>
          </div>
      </div>
      <!-- Card Kamar -->
      <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
              <div class="card-body">
                  <div class="card-title d-flex align-items-start justify-content-between">
                      <div class="avatar flex-shrink-0">
                          <i class="bx bxs-box text-warning" style="font-size: 2.2rem;"></i>
                      </div>
                  </div>
                  <span class="fw-semibold d-block mb-1">Stok Barang</span>
                  <h3 class="card-title text-nowrap mb-2"><?= 20 ?></h3>
              </div>
          </div>
      </div>
      <!-- Card Booking -->
      <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
              <div class="card-body">
                  <div class="card-title d-flex align-items-start justify-content-between">
                      <div class="avatar flex-shrink-0">
                          <i class="bx bxs-calendar-check text-success" style="font-size: 2.2rem;"></i>
                      </div>
                  </div>
                  <span class="fw-semibold d-block mb-1">Total Penjualan</span>
                  <h3 class="card-title text-nowrap mb-2"><?= 19 ?></h3>
              </div>
          </div>
      </div>

      <!-- Grafik Booking Bulanan -->
      <div class="col-12 mb-4">
          <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">Statistik Penjualan Bulanan</h5>
              </div>
              <div class="card-body">
                  <canvas id="bookingChart"></canvas>
              </div>
          </div>
      </div>

    @endif

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('bookingChart');

        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($bookingLabels),
                    datasets: [{
                        label: 'Jumlah Booking',
                        data: @json($bookingCounts),
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(255, 159, 64, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>

@endsection
