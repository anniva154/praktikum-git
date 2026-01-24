@extends('layouts.backend')

@section('title', 'Dashboard Pengguna')

@section('content')

<div class="row g-3 mb-3">

  {{-- KOLOM KIRI (WELCOME + STATS) --}}
  <div class="col-lg-8">

    {{-- WELCOME --}}
    <div class="card welcome-card-white mb-3">
      <div class="card-body d-flex align-items-center">
        <img src="{{ asset('assets/img/sapa.png') }}" class="welcome-img me-3" alt="welcome">
        <div>
          <h4 class="fw-bold mb-1">
            👋 Hallo {{ Auth::user()->name }},
            Selamat Datang di <span class="text-primary">SIMLAB</span>
          </h4>
          <p class="mb-0 text-muted">
            Setiap percobaan di laboratorium adalah langkah menuju penemuan baru.
            Tetap semangat, fokus, dan jadilah ilmuwan hebat!
          </p>
        </div>
      </div>
    </div>

    {{-- CARD STATS --}}
    <div class="row g-3">

      {{-- PEMINJAMAN --}}
      <div class="col-md-6">
        <div class="card dashboard-card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-1">
              <h4 class="fw-bold text-info mb-0">
                {{ $totalPeminjaman ?? 0 }}
              </h4>
              <i class="bi bi-arrow-left-right dashboard-icon text-info"></i>
            </div>
            <p class="dashboard-label mb-1 text-secondary">Peminjaman</p>
            <small class="text-muted">Total pengajuan kamu</small>
          </div>
        </div>
      </div>

      {{-- KERUSAKAN --}}
      <div class="col-md-6">
        <div class="card dashboard-card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-1">
              <h4 class="fw-bold text-danger mb-0">
                {{ $totalKerusakan ?? 0 }}
              </h4>
              <i class="bi bi-exclamation-triangle dashboard-icon text-danger"></i>
            </div>
            <p class="dashboard-label mb-1 text-secondary">Laporan Kerusakan</p>
            <small class="text-muted">Barang yang kamu laporkan</small>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- KOLOM KANAN (KALENDER) --}}
  <div class="col-lg-4">
    <div class="card calendar-card-modern h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span id="calPrev" class="cal-nav">❮</span>
          <span id="calTitle" class="fw-semibold"></span>
          <span id="calNext" class="cal-nav">❯</span>
        </div>
        <div class="calendar-grid" id="calendarGrid"></div>
      </div>
    </div>
  </div>

</div>

@endsection


@push('styles')
<style>
  .welcome-card-white{
  background:#fff;
  border-radius:16px;
  box-shadow:0 4px 12px rgba(0,0,0,.05)
}
.welcome-img{width:80px;height:auto}

/* KALENDER */
.calendar-card-modern{
  border-radius:16px;
  background:#fff;
  box-shadow:0 4px 12px rgba(0,0,0,.05)
}
.cal-nav{cursor:pointer;font-weight:bold}
.calendar-grid{
  display:grid;
  grid-template-columns:repeat(7,1fr);
  gap:6px;
  font-size:.85rem
}
.calendar-grid div{
  text-align:center;
  padding:6px 0;
  border-radius:8px
}
.calendar-grid .day-name{font-weight:600;color:#888}
.calendar-grid .today{
  background:#0d6efd;
  color:#fff;
  font-weight:600
}

/* STAT CARD */
.dashboard-card{
  border-radius:14px;
  transition:.25s;
  border:0;
}
.dashboard-card:hover{
  transform:translateY(-4px);
  box-shadow:0 8px 20px rgba(0,0,0,.08)
}

.dashboard-icon{
  font-size:2.6rem;
  opacity:.35
}

.dashboard-label{
  font-weight:600;
  color:#555
}

/* VARIAN WARNA HALUS */

.stat-card.info h4,
.stat-card.info i{
  color:#0dcaf0;
}

.stat-card.danger h4,
.stat-card.danger i{
  color:#dc3545;
}

</style>
@endpush

@push('scripts')
<script>
let date = new Date();
const calTitle = document.getElementById('calTitle');
const calGrid = document.getElementById('calendarGrid');

function renderCalendar(){
  calGrid.innerHTML='';
  const year = date.getFullYear();
  const month = date.getMonth();
  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month+1, 0).getDate();
  calTitle.innerText = date.toLocaleDateString('id-ID',{month:'long',year:'numeric'});

  const dayNames=['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
  dayNames.forEach(d=>{
    const div=document.createElement('div');div.classList.add('day-name');div.innerText=d;calGrid.appendChild(div);
  });

  for(let i=0;i<firstDay;i++){calGrid.appendChild(document.createElement('div'));}

  for(let d=1;d<=daysInMonth;d++){
    const div=document.createElement('div');div.innerText=d;
    const today=new Date();
    if(d===today.getDate() && month===today.getMonth() && year===today.getFullYear()){
      div.classList.add('today');
    }
    calGrid.appendChild(div);
  }
}

renderCalendar();

document.getElementById('calPrev').onclick=()=>{date.setMonth(date.getMonth()-1);renderCalendar();}
document.getElementById('calNext').onclick=()=>{date.setMonth(date.getMonth()+1);renderCalendar();}
</script>
@endpush