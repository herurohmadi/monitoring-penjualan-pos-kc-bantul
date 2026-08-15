<style>
    /* ====== STYLE UMUM MODAL ====== */
    #detailModal .modal-content {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: none;
    }

    #detailModal .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 12px 18px;
    }

    #detailModal .modal-title {
        font-weight: 700;
        font-size: 16px;
        color: #313234;
        letter-spacing: 0.5px;
    }

    #detailModal .modal-body {
        padding: 20px;
    }

    /* ====== STYLE TAB ====== */
    #activityTabs {
        border-bottom: 1px solid #dee2e6;
    }

    #activityTabs .nav-link {
        font-weight: 500;
        color: #495057;
        border-radius: 0.5rem 0.5rem 0 0;
        padding: 8px 16px;
        transition: all 0.2s ease-in-out;
    }

    #activityTabs .nav-link.active {
        color: #0d6efd;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-bottom: none;
    }

    #activityTabs .badge {
        font-size: 13px;
        margin-left: 4px;
    }

    /* Modern tabs style */
    #activityTabs .nav-link.active {
        color: #fff !important;
        background-color: #0d6efd !important;
        /* border-radius: 50px; */
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.3);
    }

    #activityTabs .badge {
        font-size: 0.75rem;
        padding: 0.25em 0.6em;
    }

    /* ====== STYLE CARD ISI TAB ====== */
    #detailModal .card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        border: 1px solid #e9ecef;
        margin-bottom: 1rem;
    }

    #detailModal .card-body {
        padding: 16px;
    }

    /* ====== STYLE FOTO ====== */
    .image-wrapper {
        position: relative;
        display: block;
        width: 100%;
        max-height: 220px;
        overflow: hidden;
        margin-bottom: 8px;
        border-radius: 8px;
    }

    .image-wrapper img {
        width: 100%;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
    }

    .preview-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.65);
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 5px;
        font-size: 15px;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .image-wrapper:hover .preview-button {
        opacity: 1;
    }

    /* ====== STYLE DETAIL DATA (Label: Value) ====== */
    .detail-row {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 4px;
        font-size: 14px;
        line-height: 1.5;
    }

    .detail-label {
        flex: 0 0 180px;
        font-weight: 600;
        color: #333;
        position: relative;
    }

    .detail-label::after {
        content: ":";
        position: absolute;
        right: 5px;
    }

    .detail-value {
        flex: 1;
        color: #555;
        word-break: break-word;
    }

    /* ====== STYLE SECTION ====== */
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #0d6efd;
        margin-top: 18px;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    /* ====== RESPONSIVE ====== */
    @media (max-width: 576px) {
        #detailModal .modal-body {
            padding: 14px;
        }

        #activityTabs .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            max-width: 120px;
            padding: 6px 8px;
            font-size: 14px;
        }

        .detail-row {
            flex-direction: column;
            margin-bottom: 8px;
        }

        .detail-label {
            flex: unset;
            margin-bottom: 2px;
        }

        .detail-label::after {
            content: "";
        }

        .detail-value {
            padding-left: 4px;
        }

        .image-wrapper img {
            max-height: 180px;
        }
    }
</style>
