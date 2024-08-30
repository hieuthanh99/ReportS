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
    $trail->push('Tông hợp, báo cáo',null);
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
    $trail->push('Danh sách loại văn bản', route('document_categories.index'));
});

Breadcrumbs::for('CLVB', function ($trail) {
    $trail->parent('VB');
    $trail->push('Thêm mới loại văn bản', route('document_categories.create'));
});

Breadcrumbs::for('ULVB', function ($trail, $documentCategory) {
    $trail->parent('VB');
    $trail->push('Cập nhật loại văn bản', route('document_categories.update', $documentCategory));
});
Breadcrumbs::for('CTLVB', function ($trail) {
    $trail->parent('VB');
    $trail->push('Chi tiết loại văn bản', route('document_categories.show'));
});


/// văn bản
Breadcrumbs::for('DMVB', function ($trail) {
    $trail->parent('VB');
    $trail->push('Danh sách văn bản', route('documents.index'));
});

Breadcrumbs::for('CDMVB', function ($trail) {
    $trail->parent('VB');
    $trail->push('Thêm mới văn bản', route('documents.create'));
});

Breadcrumbs::for('UDMVB', function ($trail, $document) {
    $trail->parent('VB');
    $trail->push('Cập nhật văn bản', route('documents.update', $document));
});
Breadcrumbs::for('CTDMVB', function ($trail, $document) {
    $trail->parent('VB');
    $trail->push('Chi tiết văn bản', route('documents.show', $document));
});

//Tổ chức
Breadcrumbs::for('DSO', function ($trail) {
    $trail->parent('QLDM');
    $trail->push('Danh sách tổ chức', route('organizations.index'));
});

Breadcrumbs::for('CO', function ($trail) {
    $trail->parent('QLDM');
    $trail->push('Thêm mới tổ chức', route('organizations.create'));
});

Breadcrumbs::for('UO', function ($trail, $document) {
    $trail->parent('QLDM');
    $trail->push('Cập nhật tổ chức', route('organizations.update', $document));
});
Breadcrumbs::for('CTO', function ($trail, $document) {
    $trail->parent('QLDM');
    $trail->push('Chi tiết tổ chức', route('organizations.show', $document));
});

//Loại Tổ chức
Breadcrumbs::for('DSLO', function ($trail) {
    $trail->parent('QLDM');
    $trail->push('Danh sách loại tổ chức', route('organization_types.index'));
});

Breadcrumbs::for('CLO', function ($trail) {
    $trail->parent('QLDM');
    $trail->push('Thêm mới loại tổ chức', route('organization_types.create'));
});

Breadcrumbs::for('ULO', function ($trail, $document) {
    $trail->parent('QLDM');
    $trail->push('Cập nhật loại tổ chức', route('organization_types.update', $document));
});
Breadcrumbs::for('CTLO', function ($trail, $document) {
    $trail->parent('QLDM');
    $trail->push('Chi tiết loại tổ chức', route('organization_types.show', $document));
});

//Nhóm nhiệm vụ
Breadcrumbs::for('DSNV', function ($trail) {
    $trail->parent('NNV');
    $trail->push('Danh sách nhóm nhiệm vụ', route('task_groups.index'));
});

Breadcrumbs::for('CLNV', function ($trail) {
    $trail->parent('NNV');
    $trail->push('Thêm mới nhóm nhiệm vụ', route('task_groups.create'));
});

Breadcrumbs::for('ULNV', function ($trail, $document) {
    $trail->parent('NNV');
    $trail->push('Cập nhật nhóm nhiệm vụ', route('task_groups.update', $document));
});

//Nhóm chỉ tiêu
Breadcrumbs::for('DSCT', function ($trail) {
    $trail->parent('NCT');
    $trail->push('Danh sách nhóm chỉ tiêu', route('indicator_groups.index'));
});

Breadcrumbs::for('CLCT', function ($trail) {
    $trail->parent('NCT');
    $trail->push('Thêm mới nhóm chỉ tiêu', route('indicator_groups.create'));
});

Breadcrumbs::for('ULCT', function ($trail, $document) {
    $trail->parent('NCT');
    $trail->push('Cập nhật nhóm chỉ tiêu', route('indicator_groups.update', $document));
});

//Danh mục chức vụ
Breadcrumbs::for('DSCN', function ($trail) {
    $trail->parent('DMCN');
    $trail->push('Danh sách chức năng', route('positions.index'));
});

Breadcrumbs::for('CLCN', function ($trail) {
    $trail->parent('DMCN');
    $trail->push('Thêm mới chức năng', route('positions.create'));
});

Breadcrumbs::for('ULCN', function ($trail, $document) {
    $trail->parent('DMCN');
    $trail->push('Cập nhật chức năng', route('positions.update', $document));
});

//Tổng hợp, thống kê
Breadcrumbs::for('THTVB', function ($trail) {
    $trail->parent('THTK');
    $trail->push('Báo cáo tổng hợp theo văn bản', route('reports.withDocument'));
});

Breadcrumbs::for('THTDV', function ($trail) {
    $trail->parent('THTK');
    $trail->push('Báo cáo tổng hợp theo đơn vị', route('reports.withUnit'));
});

//Tổng hợp, thống kê
Breadcrumbs::for('DSBC', function ($trail) {
    $trail->parent('THBC');
    $trail->push('Danh sách báo cáo', route('documents.report'));
});

Breadcrumbs::for('CTBC', function ($trail, $document) {
    $trail->parent('THBC');
    $trail->push('Thêm mới báo cáo', route('documents.report.details', $document));
});

Breadcrumbs::for('UBC', function ($trail, $document) {
    $trail->parent('THBC');
    $trail->push('Cập nhật báo cáo', route('documents.report.update', $document));
});