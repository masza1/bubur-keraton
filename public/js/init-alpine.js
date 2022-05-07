document.addEventListener('alpine:init', () => {
    Alpine.data('data', () => ({
        isProfileMenuOpen: false,
        toggleProfileMenu() {
            this.isProfileMenuOpen = !this.isProfileMenuOpen
        },

        closeProfileMenu() {
            this.isProfileMenuOpen = false
        },

        isSideMenuOpen: false,
        toggleSideMenu() {
            this.isSideMenuOpen = !this.isSideMenuOpen
        },

        closeSideMenu() {
            this.isSideMenuOpen = false
        },

        isMultiLevelMenuOpen: false,
        toggleMultiLevelMenu() {
            this.isMultiLevelMenuOpen = !this.isMultiLevelMenuOpen
        },
        // Modal
        isModalOpen: false,
        trapCleanup: null,
        openModal(e, args, modalId) {
            if (document.querySelector(modalId) != null) {
                this.isModalOpen = true
                let modal = $(modalId)
                window[args](e, modal)
                modal.attr('x-show', 'isModalOpen')
                this.trapCleanup = focusTrap(document.querySelector(modalId))
            } else {
                alert('modal tidak ada')
            }
        },
        closeModal() {
            let modal = $('div[x-show="isModalOpen"][role="dialog"]')
            modal.attr('x-show', false)
            this.isModalOpen = false
            this.trapCleanup()
        }
    }))
})