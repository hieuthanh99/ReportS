<?php

use App\Models\DocumentCategory;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Trang chủ', route('dashboard'));
});


Breadcrumbs::for('QLDM', function ($trail) {
    $trail->parent('home');
    $trail->push('Quản lý danh mục',null);
});


Breadcrumbs::for('QLCV', function ($trail) {
    $trail->parent('home');
    $trail->push('Quản lý công việc',null);
});

Breadcrumbs::for('THBC', function ($trail) {
    $trail->parent('home');
    $trail->push('Tổng hợp, báo cáo',null);
});
Breadcrumbs::for('BCKQ', function ($trail) {
    $trail->parent('THBC');
    $trail->push('Phê duyệt kết quả công việc',null);
});

//nhiệm vu
Breadcrumbs::for('tasks.byType', function ($trail, $type) {
    $trail->parent('QLCV');
    if($type == 'task')     $trail->push('Danh sách nhiệm vụ', null);
    else $trail->push('Danh sách chỉ tiêu', null);
});

Breadcrumbs::for('tasks.byType.approved', function ($trail, $type) {
    $trail->parent('QLCV');
    if($type == 'task')     $trail->push('Phê duyệt báo cáo nhiệm vụ', null);
    else $trail->push('Phê duyệt báo cáo chỉ tiêu', null);
});


Breadcrumbs::for('create.tasks.byType', function ($trail, $type) {
    $trail->parent('tasks.byType', $type);
    if($type == 'task')     $trail->push('Thêm mới nhiệm vụ', null);
    else $trail->push('Thêm mới chỉ tiêu', null);
});

Breadcrumbs::for('update.tasks.byType', function ($trail, $type) {
    $trail->parent('tasks.byType', $type);
    if($type == 'task')     $trail->push('Cập nhật nhiệm vụ', null);
    else $trail->push('Cập nhật chỉ tiêu', null);
});

Breadcrumbs::for('details.tasks.byType', function ($trail, $type) {
    $trail->parent('tasks.byType', $type);
    if($type == 'task')     $trail->push('Chi tiết nhiệm vụ', null);
    else $trail->push('Chi tiết chỉ tiêu', null);
});


Breadcrumbs::for('VB', function ($trail) {
    $trail->parent('QLDM');
    $trail->push('Văn bản',null);
});

Breadcrumbs::for('CQ', function ($trail) {
    $trail->parent('QLDM');
    $trail->push('Cơ quan',null);
});

Breadcrumbs::for('NNV', function ($trail) {
    $trail->parent('QLDM');
    $trail->push('Nhóm nhiệm vụ',null);
});

Breadcrumbs::for('NCT', function ($trail) {
    $trail->parent('QLDM');
    $trail->push('Nhóm chỉ tiêu',null);
});

Breadcrumbs::for('ND', function ($trail) {
    $trail->parent('QLDM');
    $trail->push('Người dùng',null);
});


//Nguoi dùng
Breadcrumbs::for('DSTK', function ($trail) {
    $trail->parent('ND');
    $trail->push('Danh sách người dùng',null);
});
Breadcrumbs::for('CTK', function ($trail) {
    $trail->parent('DSTK');
    $trail->push('Thêm mới người dùng',null);
});
Breadcrumbs::for('CNTK', function ($trail) {
    $trail->parent('DSTK');
    $trail->push('Cập nhật người dùng',null);
});


Breadcrumbs::for('DMCN', function ($trail) {
    $trail->parent('ND');
    $trail->push('Danh mục chức năng',null);
});

Breadcrumbs::for('THTK', function ($trail) {
    $trail->parent('THBC');
    $trail->push('Tổng hợp, thống kê',null);
});
/// Loại văn bản
Breadcrumbs::for('LVB', function ($trail) {
    $trail->parent('VB');
    $trail->push('Danh sách loại văn bản', null);
});

Breadcrumbs::for('CLVB', function ($trail) {
    $trail->parent('VB');
    $trail->push('Thêm mới loại văn bản', null);
});

Breadcrumbs::for('ULVB', function ($trail, $documentCategory) {
    $trail->parent('VB');
    $trail->push('Cập nhật loại văn bản', null);
});
Breadcrumbs::for('CTLVB', function ($trail) {
    $trail->parent('VB');
    $trail->push('Chi tiết loại văn bản', null);
});


/// văn bản
Breadcrumbs::for('DMVB', function ($trail) {
    $trail->parent('VB');
    $trail->push('Danh sách văn bản', null);
});

Breadcrumbs::for('CDMVB', function ($trail) {
    $trail->parent('VB');
    $trail->push('Thêm mới văn bản', null);
});

Breadcrumbs::for('UDMVB', function ($trail, $document) {
    $trail->parent('VB');
    $trail->push('Cập nhật văn bản', null);
});
Breadcrumbs::for('CTDMVB', function ($trail, $document) {
    $trail->parent('VB');
    $trail->push('Chi tiết văn bản', null);
});

//Tổ chức
Breadcrumbs::for('CQTC', function ($trail) {
    $trail->parent('QLDM');
    $trail->push('Cơ quan, tổ chức', null);
});

Breadcrumbs::for('DSO', function ($trail) {
    $trail->parent('CQTC');
    $trail->push('Danh sách cơ quan', null);
});

Breadcrumbs::for('CO', function ($trail) {
    $trail->parent('DSO');
    $trail->push('Thêm mới cơ quan', null);
});

Breadcrumbs::for('UO', function ($trail, $document) {
    $trail->parent('DSO');
    $trail->push('Cập nhật cơ quan', null);
});
Breadcrumbs::for('CTO', function ($trail, $document) {
    $trail->parent('DSO');
    $trail->push('Chi tiết cơ quan', null);
});

//Loại Tổ chức
Breadcrumbs::for('DSLO', function ($trail) {
    $trail->parent('CQTC');
    $trail->push('Danh sách phân loại cơ quan', null);
});

Breadcrumbs::for('CLO', function ($trail) {
    $trail->parent('QLDM');
    $trail->push('Thêm mới loại cơ quan', null);
});

Breadcrumbs::for('ULO', function ($trail, $document) {
    $trail->parent('QLDM');
    $trail->push('Cập nhật loại cơ quan', null);
});
Breadcrumbs::for('CTLO', function ($trail, $document) {
    $trail->parent('QLDM');
    $trail->push('Chi tiết loại cơ quan', null);
});

//Nhóm nhiệm vụ
Breadcrumbs::for('DSNV', function ($trail) {
    $trail->parent('NNV');
    $trail->push('Danh sách nhóm nhiệm vụ', null);
});

Breadcrumbs::for('CLNV', function ($trail) {
    $trail->parent('NNV');
    $trail->push('Thêm mới nhóm nhiệm vụ', null);
});

Breadcrumbs::for('ULNV', function ($trail, $document) {
    $trail->parent('NNV');
    $trail->push('Cập nhật nhóm nhiệm vụ', null);
});

//Nhóm chỉ tiêu
Breadcrumbs::for('DSCT', function ($trail) {
    $trail->parent('NCT');
    $trail->push('Danh sách nhóm chỉ tiêu', null);
});

Breadcrumbs::for('CLCT', function ($trail) {
    $trail->parent('NCT');
    $trail->push('Thêm mới nhóm chỉ tiêu', null);
});

Breadcrumbs::for('ULCT', function ($trail, $document) {
    $trail->parent('NCT');
    $trail->push('Cập nhật nhóm chỉ tiêu', null);
});

//Danh mục chức vụ
Breadcrumbs::for('DSCV', function ($trail) {
    $trail->parent('ND');
    $trail->push('Danh sách chức vụ', null);
});

Breadcrumbs::for('CCV', function ($trail) {
    $trail->parent('DSCV');
    $trail->push('Thêm mới chức vụ', null);
});

Breadcrumbs::for('UCV', function ($trail, $document) {
    $trail->parent('DSCV');
    $trail->push('Cập nhật chức vụ', null);
});

//Tổng hợp, thống kê
Breadcrumbs::for('THTVB', function ($trail) {
    $trail->parent('THBC');
    $trail->push('Báo cáo tổng hợp theo văn bản', null);
});

Breadcrumbs::for('THTDV', function ($trail) {
    $trail->parent('THBC');
    $trail->push('Báo cáo tổng hợp theo đơn vị', null);
});

Breadcrumbs::for('THTCK', function ($trail) {
    $trail->parent('THBC');
    $trail->push('Báo cáo tổng hợp theo chu kỳ', null);
});

Breadcrumbs::for('THCT', function ($trail) {
    $trail->parent('THBC');
    $trail->push('Báo cáo tổng hợp chi tiết', null);
});

//Tổng hợp, thống kê
Breadcrumbs::for('DSBC', function ($trail) {
    $trail->parent('BCKQ');
    $trail->push('Danh sách báo cáo nhiệm vụ', null );
});

//Tổng hợp, thống kê
Breadcrumbs::for('DSBCTG', function ($trail) {
    $trail->parent('BCKQ');
    $trail->push('Danh sách báo cáo chỉ tiêu', null);
});

Breadcrumbs::for('CTBC', function ($trail, $document) {
    $trail->parent('DSBC');
    $trail->push('Chi tiết báo cáo', null);
});

Breadcrumbs::for('UBC', function ($trail, $document) {
    $trail->parent('DSBC');
    $trail->push('Báo cáo nhiệm vụ', null);
});


Breadcrumbs::for('CTBCTG', function ($trail, $document) {
    $trail->parent('DSBCTG');
    $trail->push('Chi tiết báo cáo', null);
});

Breadcrumbs::for('UBCTG', function ($trail, $document) {
    $trail->parent('DSBCTG');
    $trail->push('Báo cáo chỉ tiêu', null);
});
