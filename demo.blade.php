@php
    use App\Helpers\CommonHelper;
    $mainModuleId = request()->input('mainModuleId');
    $image_base_path = $output_directory->option_value ?? '';

@endphp

<input type="hidden" id="image_base_path" value="{{ $image_base_path }}">

<!-- Camera Grid Container -->
<div id="cameraGridContainer">
    <div class="row g-3" id="cameraGrid">
        @forelse ($image_array as $row)
            @php
                $cam_id = $row['cam_id'];
                $isBlinking = in_array($cam_id, $blinking_Cam ?? []);
                $cardClass = $isBlinking ? 'blink-bg border-pulse' : '';
                $camera_name = ucfirst($row['camera_name']);
                $cameraActiveStatus = $row['cameraActiveStatus'];

                // The modal URL is the same for all modules on this landing dashboard.
                $modalUrl = route('landing.ViewCamDetails');

                $image_base = '';
                if ($row['full_image'] && file_exists($row['full_image'])) {
                    // dd($row['full_image']);
                    $image_base = base64_encode(file_get_contents($row['full_image']));
                }
            @endphp

            <!-- Premium Camera Card -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="premium-camera-card {{ $cardClass }}"
                    onclick="open_image(this, {{ $cam_id }}, '{{ $modalUrl }}')">
                    <!-- Camera Image Container -->
                    <div class="camera-image-container" style="height: 100%;width: 100%; aspect-ratio: 16/9;">
                        @if ($cameraActiveStatus && $image_base)
                            <img src="data:image/jpeg;base64,{{ $image_base }}" class="camera-image" alt="{{ $camera_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="camera-placeholder" style="background-color: #f5f5f5;height: 100%;width: 100%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-video-slash" style="font-size: 24px; color: #999;"></i>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="camera-status {{ $cameraActiveStatus ? 'online' : 'offline' }}">
                            <span class="status-dot"></span>
                            {{ $cameraActiveStatus ? 'LIVE' : 'OFF' }}
                        </div>

                        <!-- Violation Badge -->
                        @php $totalViolations = collect($row['rtsp_data'])->sum('violation_cnt'); @endphp
                        @if ($totalViolations > 0)
                            <div class="violation-badge">
                                <span class="violation-count">{{ $totalViolations }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Card Info -->
                    <div class="camera-info">
                        <div class="camera-name" title="{{ $camera_name }}">
                            {{ Str::limit($camera_name, 15) }}
                        </div>

                        <!-- Model Indicators -->
                        @if (count($row['rtsp_data']) > 0)
                            <div class="model-indicators">
                                @foreach ($row['rtsp_data'] as $data)
                                    @if ($data['violation_cnt'] > 0)
                                        <div class="model-icon" data-bs-toggle="tooltip"
                                            title="{{ $data['model_name'] }}: {{ $data['violation_cnt'] }}">
                                            <img src="{{ url('public/assets/images/models/' . $data['model_name'] . '.png') }}"
                                                alt="{{ $data['model_name'] }}">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <x-no-data-card />
            </div>
        @endforelse
        <!-- @if (count($image_array) >= 1) -->
            <!-- @for ($i = 1; $i <= 16; $i++) -->
                <!-- <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="premium-camera-card"
                        onclick="open_image(this, {{ 54 + $i }}, 'http://localhost/safetyeye/landing-ViewCamDetails')">

                        <div class="camera-image-container">


                            <?php

                if ($i == 1) {
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod4\\kraylon\\bin\\predictions\\CAM21\\Shopfloor_frame_20251114_062702853557.jpg'));
                } elseif ($i == 2) {
                  
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod1\\kraylon\\bin\\predictions\\Flemming\\Flemming_frame_00000428.jpg'));
                } elseif ($i == 3) {
                    
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod1\\kraylon\\bin\\predictions\\EdisonLab\\EdisonLab_frame_00002658.jpg'));
                } elseif ($i == 4) {
                    
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod1\\kraylon\\bin\\predictions\\Flemming\\Flemming_frame_00004558.jpg'));
                } elseif ($i == 5) {
                    
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod1\\kraylon\\bin\\predictions\\EdisonLab\\EdisonLab_frame_00002658.jpg'));
                } elseif ($i == 6) {
                    
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod1\\kraylon\\bin\\predictions\\Flemming\\Flemming_frame_00004558.jpg'));
                } elseif ($i == 7) {
                    
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod1\\kraylon\\bin\\predictions\\EdisonLab\\EdisonLab_frame_00002658.jpg'));
                } elseif ($i == 8) {
                    
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod1\\kraylon\\bin\\predictions\\Flemming\\Flemming_frame_00004558.jpg'));
                } elseif ($i == 9) {
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod4\\kraylon\\bin\\predictions\\CAM21\\Shopfloor_frame_20251114_062702853557.jpg'));
                } elseif ($i == 10) {
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod4\\kraylon\\bin\\predictions\\CAM21\\Shopfloor_frame_20251114_062702853557.jpg'));
                } elseif ($i == 11) {
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod4\\kraylon\\bin\\predictions\\CAM21\\Shopfloor_frame_20251114_062702853557.jpg'));
                } elseif ($i == 12) {
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod4\\kraylon\\bin\\predictions\\CAM21\\Shopfloor_frame_20251114_062702853557.jpg'));
                } elseif ($i == 13) {
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod4\\kraylon\\bin\\predictions\\CAM21\\Shopfloor_frame_20251114_062702853557.jpg'));
                } elseif ($i == 14) {
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod4\\kraylon\\bin\\predictions\\CAM21\\Shopfloor_frame_20251114_062702853557.jpg'));
                } elseif ($i == 15) {
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod4\\kraylon\\bin\\predictions\\CAM21\\Shopfloor_frame_20251114_062702853557.jpg'));
                } elseif ($i == 16) {
                    $dummyImage = base64_encode(file_get_contents('C:\\STA\\phase1\\mod4\\kraylon\\bin\\predictions\\CAM21\\Shopfloor_frame_20251114_062702853557.jpg'));
                }


                            ?>








                            <div class="camera-placeholder">
                                <img src="data:image/jpeg;base64,{{ $dummyImage }}" class="camera-image" alt="CAM{{ $i }}">
                            </div>

                            <div class="camera-status online">
                                <span class="status-dot"></span>
                                LIVE
                            </div>
                        </div>

                       
                        <div class="camera-info">
                            <div class="camera-name" title="CAM{{ $i }}">
                                CAM{{ $i }}
                            </div>
                            <div class="model-indicators"></div>
                        </div>
                    </div>
                </div> -->
            <!-- @endfor -->
        <!-- @endif -->
    </div>
</div>