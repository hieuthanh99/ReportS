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