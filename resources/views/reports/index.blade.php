@extends('layouts.app')
@section('title', 'รายงาน')

@section('content')
    <style>
        .report-card {
            background: #fff;
            border: 1px solid var(--border, #e4e1dc);
            border-radius: 16px;
            padding: 1.6rem;
            height: 100%;
            text-decoration: none;
            color: inherit;
            display: block;
            transition: box-shadow .2s, transform .2s;
        }

        .report-card:hover {
            box-shadow: 0 4px 14px rgba(28, 26, 23, .08);
            transform: translateY(-2px);
            color: inherit;
        }

        .report-card .icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .report-card .title {
            font-family: 'Prompt', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: .4rem;
        }

        .report-card .desc {
            font-size: .85rem;
            color: var(--muted, #6b655e);
        }
    </style>

    <div class="breadcrumb-sm">รายงาน</div>
    <div class="mb-4">
        <h1 class="page-title mb-0">รายงาน</h1>
        <div class="page-sub">เลือกรายงานที่ต้องการดูหรือส่งออก</div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <a href="{{ route('reports.students') }}" class="report-card">
                <div class="icon-box" style="background:var(--accent-soft,#e7ebf1);color:var(--accent-dark,#13233a);">
                    <i class="bi bi-people"></i>
                </div>
                <div class="title">รายงานนักเรียน</div>
                <div class="desc">จำนวนนักเรียน, นักเรียนใหม่, แยกตามคอร์ส/สาขา/เครื่องดนตรี</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('reports.revenue') }}" class="report-card">
                <div class="icon-box" style="background:var(--success-soft,#e9f9ef);color:var(--success,#2f6f4e);">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="title">รายงานรายได้</div>
                <div class="desc">รายวัน/สัปดาห์/เดือน/ปี แยกตามช่องทางชำระเงินและสาขา</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('reports.teacher-performance') }}" class="report-card">
                <div class="icon-box" style="background:var(--amber-soft,#fdf1e2);color:var(--amber,#8a5a2b);">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <div class="title">รายงาน Performance อาจารย์</div>
                <div class="desc">ชั่วโมงสอน, จำนวนนักเรียน, จำนวนการลา, จำนวนคลาส แยกตามสาขา</div>
            </a>
        </div>
    </div>
@endsection
