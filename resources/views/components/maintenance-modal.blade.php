@props(['ground', 'routeName' => 'admin.grounds.schedule-maintenance'])

<!-- Maintenance Modal -->
<div id="maintenance-modal-{{ $ground->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-tools text-yellow-600 mr-2"></i>
                    {{ $ground->is_under_maintenance ? 'Update Maintenance' : 'Schedule Maintenance' }}
                </h3>
                <button onclick="closeMaintenanceModal({{ $ground->id }})" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ $ground->name }}</p>
                        <p class="text-sm text-gray-600">{{ $ground->location }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs rounded-full {{ $ground->is_under_maintenance ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            {{ $ground->is_under_maintenance ? 'Under Maintenance' : 'Available' }}
                        </span>
                    </div>
                </div>
            </div>

            @if($ground->is_under_maintenance)
                <!-- ===== FORM 1: GROUND UNDER MAINTENANCE ===== -->
                <form id="maintenance-form-{{ $ground->id }}" action="{{ route($routeName, $ground) }}" method="POST">
                    @csrf
                    <input type="hidden" name="maintenance_action" value="end">
                    
                    <div class="mb-6 p-4 bg-green-50 rounded-lg border-2 border-green-300">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-600 text-xl mr-3 mt-1"></i>
                            <div>
                                <p class="font-semibold text-green-900">Ground is Under Maintenance</p>
                                <p class="text-sm text-green-800 mt-2">Click below to make this ground available for bookings immediately (next hour onwards).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" 
                                onclick="closeMaintenanceModal({{ $ground->id }})" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white rounded-md bg-green-600 hover:bg-green-700 flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            Make Available Now
                        </button>
                    </div>
                </form>

            @else
                <!-- ===== FORM 2: GROUND AVAILABLE ===== -->
                <form id="maintenance-form-{{ $ground->id }}" action="{{ route($routeName, $ground) }}" method="POST">
                    @csrf
                    
                    <!-- GROUND IS AVAILABLE - SHOW MAINTENANCE OPTIONS -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Choose an action:</label>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-green-50 hover:border-green-400">
                                <input type="radio" name="maintenance_action" value="start-now" 
                                       class="mr-3 w-4 h-4" onclick="selectMarkMaintenanceNow({{ $ground->id }})">
                                <div>
                                    <span class="text-sm font-semibold text-gray-900">Mark Under Maintenance NOW</span>
                                    <p class="text-xs text-gray-600">✓ No dates needed • Ground goes under maintenance immediately</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-yellow-50 hover:border-yellow-400">
                                <input type="radio" name="maintenance_action" value="start" checked
                                       class="mr-3 w-4 h-4" onclick="selectScheduleMaintenance({{ $ground->id }})">
                                <div>
                                    <span class="text-sm font-semibold text-gray-900">Schedule Maintenance</span>
                                    <p class="text-xs text-gray-600">⏰ Set specific dates and time for maintenance</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- SCHEDULE SECTION - ONLY SHOWS FOR SCHEDULE MAINTENANCE OPTION -->
                    <div id="schedule-section-{{ $ground->id }}" class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Maintenance Schedule Details</h4>
                        
                        <!-- Start Date & Time -->
                        <div class="mb-3">
                            <label for="start_date_{{ $ground->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                Start Date & Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" 
                                   id="start_date_{{ $ground->id }}" 
                                   name="maintenance_start_date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500">
                        </div>

                        <!-- End Date & Time -->
                        <div class="mb-3">
                            <label for="end_date_{{ $ground->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                End Date & Time (Optional)
                            </label>
                            <input type="datetime-local" 
                                   id="end_date_{{ $ground->id }}" 
                                   name="maintenance_end_date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500">
                            <p class="text-xs text-gray-500 mt-1">Leave empty if duration is unknown</p>
                        </div>

                        <!-- Maintenance Reason -->
                        <div class="mb-4">
                            <label for="maintenance_reason_{{ $ground->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                Reason (Optional)
                            </label>
                            <textarea id="maintenance_reason_{{ $ground->id }}" 
                                      name="maintenance_reason" 
                                      rows="2" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                                      placeholder="e.g., Facility repairs, equipment maintenance"></textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" 
                                onclick="closeMaintenanceModal({{ $ground->id }})" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                id="submit-btn-{{ $ground->id }}"
                                class="px-4 py-2 text-sm font-medium text-white rounded-md bg-yellow-600 hover:bg-yellow-700">
                            <i class="fas fa-calendar mr-1"></i>
                            Schedule Maintenance
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
function closeMaintenanceModal(groundId) {
    document.getElementById('maintenance-modal-' + groundId).classList.add('hidden');
}

function openMaintenanceModal(groundId) {
    document.getElementById('maintenance-modal-' + groundId).classList.remove('hidden');
    
    // ONLY set datetime fields if ground is AVAILABLE (fields exist)
    if (document.getElementById('start_date_' + groundId)) {
        const now = new Date();
        const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
        const startInput = document.getElementById('start_date_' + groundId);
        const endInput = document.getElementById('end_date_' + groundId);
        
        if (startInput) {
            startInput.min = localDateTime;
            if (!startInput.value) {
                startInput.value = localDateTime;
            }
        }
        
        if (endInput) {
            endInput.min = localDateTime;
        }
    }
}

// FOR IMMEDIATE ACTION (Mark Under Maintenance Now) - NO DATES NEEDED
function selectMarkMaintenanceNow(groundId) {
    // Hide date section
    const scheduleSection = document.getElementById('schedule-section-' + groundId);
    scheduleSection.classList.add('hidden');
    
    // Disable all date inputs so they don't get sent
    const startInput = document.getElementById('start_date_' + groundId);
    const endInput = document.getElementById('end_date_' + groundId);
    const reasonInput = document.getElementById('maintenance_reason_' + groundId);
    
    if (startInput) startInput.disabled = true;
    if (endInput) endInput.disabled = true;
    if (reasonInput) reasonInput.disabled = true;
}

// FOR SCHEDULED ACTION (Schedule with dates) - REQUIRES DATES
function selectScheduleMaintenance(groundId) {
    // Show date section
    const scheduleSection = document.getElementById('schedule-section-' + groundId);
    scheduleSection.classList.remove('hidden');
    
    // Enable all date inputs
    const startInput = document.getElementById('start_date_' + groundId);
    const endInput = document.getElementById('end_date_' + groundId);
    const reasonInput = document.getElementById('maintenance_reason_' + groundId);
    
    if (startInput) startInput.disabled = false;
    if (endInput) endInput.disabled = false;
    if (reasonInput) reasonInput.disabled = false;
}

// Form validation for maintenance dates
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('[id^="maintenance-form-"]');
    forms.forEach(form => {
        const groundId = form.id.split('-').pop();
        const startInput = document.getElementById('start_date_' + groundId);
        const endInput = document.getElementById('end_date_' + groundId);
        
        if (startInput && endInput) {
            startInput.addEventListener('change', function() {
                if (this.value) {
                    endInput.min = this.value;
                }
            });
        }
        
        form.addEventListener('submit', function(e) {
            // Get the maintenance action
            const actionInput = form.querySelector('input[name="maintenance_action"]');
            const maintenanceAction = actionInput ? actionInput.value : 'end';
            
            // ONLY validate if action is 'start' (schedule with dates)
            if (maintenanceAction === 'start') {
                // Dates are required for schedule
                if (!startInput || !startInput.value) {
                    e.preventDefault();
                    alert('Start date and time is required');
                    startInput?.focus();
                    return;
                }
                
                if (endInput && startInput.value && endInput.value && startInput.value >= endInput.value) {
                    e.preventDefault();
                    alert('End date must be after start date');
                    return;
                }
            }
            // For 'end' (Make Available Now) and 'start-now', NO validation needed
        });
    });
});
</script>
