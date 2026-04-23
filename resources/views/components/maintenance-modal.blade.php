@props(['ground'])

<!-- Maintenance Modal -->
<div id="maintenance-modal-{{ $ground->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-tools text-yellow-600 mr-2"></i>
                    Schedule Maintenance
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

            <form id="maintenance-form-{{ $ground->id }}" action="{{ route('admin.grounds.schedule-maintenance', $ground) }}" method="POST">
                @csrf
                
                <!-- Maintenance Status -->
                @if(!$ground->is_under_maintenance)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maintenance Status</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="maintenance_action" value="start" checked 
                                       class="mr-2 text-yellow-600 focus:ring-yellow-500" onchange="toggleMaintenanceFields({{ $ground->id }}, 'start')">
                                <span class="text-sm">Mark Under Maintenance</span>
                            </label>
                        </div>
                    </div>
                @else
                    <!-- Hidden input for maintenance end action -->
                    <input type="hidden" name="maintenance_action" value="end">
                    <div class="mb-4 p-3 bg-yellow-50 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            This ground is currently under maintenance. Use the "Mark Available" button on the ground details page to end maintenance.
                        </p>
                    </div>
                @endif

                <!-- Schedule Section -->
                <div id="schedule-section-{{ $ground->id }}" class="{{ $ground->is_under_maintenance ? 'hidden' : '' }}">
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Maintenance Schedule</h4>
                        
                        <!-- Start Date & Time -->
                        <div class="mb-3">
                            <label for="start_date_{{ $ground->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                Start Date & Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" 
                                   id="start_date_{{ $ground->id }}" 
                                   name="maintenance_start_date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                                   required>
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
                            <p class="text-xs text-gray-500 mt-1">Leave empty if maintenance duration is unknown</p>
                        </div>

                        <!-- Maintenance Reason -->
                        <div class="mb-4">
                            <label for="maintenance_reason_{{ $ground->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                Maintenance Reason (Optional)
                            </label>
                            <textarea id="maintenance_reason_{{ $ground->id }}" 
                                      name="maintenance_reason" 
                                      rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                                      placeholder="e.g., Facility repairs, equipment maintenance, etc."></textarea>
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
                    @if(!$ground->is_under_maintenance)
                        <button type="submit" 
                                id="submit-btn-{{ $ground->id }}"
                                class="px-4 py-2 text-sm font-medium text-white rounded-md bg-yellow-600 hover:bg-yellow-700">
                            <i class="fas fa-clock mr-1"></i>
                            <span id="submit-text-{{ $ground->id }}">
                                Schedule Maintenance
                            </span>
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function closeMaintenanceModal(groundId) {
    document.getElementById('maintenance-modal-' + groundId).classList.add('hidden');
}

function openMaintenanceModal(groundId) {
    document.getElementById('maintenance-modal-' + groundId).classList.remove('hidden');
    
    // Set minimum datetime to now
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

function toggleMaintenanceFields(groundId, action) {
    const scheduleSection = document.getElementById('schedule-section-' + groundId);
    const submitBtn = document.getElementById('submit-btn-' + groundId);
    const submitText = document.getElementById('submit-text-' + groundId);
    
    if (action === 'end') {
        scheduleSection.classList.add('hidden');
        submitBtn.className = 'px-4 py-2 text-sm font-medium text-white rounded-md bg-green-600 hover:bg-green-700';
        submitText.textContent = 'Mark Available';
    } else {
        scheduleSection.classList.remove('hidden');
        submitBtn.className = 'px-4 py-2 text-sm font-medium text-white rounded-md bg-yellow-600 hover:bg-yellow-700';
        submitText.textContent = 'Schedule Maintenance';
    }
}

// Validate end date is after start date
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
            
            form.addEventListener('submit', function(e) {
                if (startInput.value && endInput.value && startInput.value >= endInput.value) {
                    e.preventDefault();
                    alert('End date must be after start date');
                }
            });
        }
    });
});
</script>
