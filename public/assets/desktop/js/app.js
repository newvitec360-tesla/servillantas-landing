/**
 * Servillantas El Puente — Desktop App JS
 * Modules: Toasts, Modals, Table Sorting, Search
 * All interactions via addEventListener (ZERO inline handlers)
 */
(function() {
  'use strict';

  /* ── Toast System ── */
  const Toast = {
    container: null,

    init: function() {
      this.container = document.getElementById('toastWrap');
    },

    show: function(title, message, duration) {
      if (!this.container) return;
      duration = duration || 4000;

      const toast = document.createElement('div');
      toast.className = 'toast';
      toast.innerHTML = '<strong>' + title + '</strong><span class="toast-msg">' + message + '</span>';
      this.container.appendChild(toast);

      setTimeout(function() {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.transition = '.3s ease';
        setTimeout(function() { toast.remove(); }, 300);
      }, duration);
    }
  };

  /* ── Modal System ── */
  const Modal = {
    show: function(id) {
      var el = document.getElementById(id);
      if (el) el.classList.add('show');
    },

    hide: function(id) {
      var el = document.getElementById(id);
      if (el) el.classList.remove('show');
    },

    init: function() {
      // Open triggers
      document.querySelectorAll('[data-modal]').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          Modal.show(this.getAttribute('data-modal'));
        });
      });

      // Close buttons
      document.querySelectorAll('.modal-close').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var backdrop = this.closest('.modal-backdrop');
          if (backdrop) backdrop.classList.remove('show');
        });
      });

      // Backdrop click
      document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
        backdrop.addEventListener('click', function(e) {
          if (e.target === this) this.classList.remove('show');
        });
      });
    }
  };

  /* ── Table Sort ── */
  const TableSort = {
    init: function() {
      document.querySelectorAll('table.sortable thead th').forEach(function(th) {
        th.addEventListener('click', function() {
          var table = this.closest('table');
          var tbody = table.querySelector('tbody');
          var rows = Array.from(tbody.querySelectorAll('tr'));
          var idx = Array.from(this.parentNode.children).indexOf(this);
          var asc = !this.classList.contains('sort-asc');

          // Reset all
          this.parentNode.querySelectorAll('th').forEach(function(h) {
            h.classList.remove('sort-asc', 'sort-desc');
          });
          this.classList.add(asc ? 'sort-asc' : 'sort-desc');

          rows.sort(function(a, b) {
            var aCell = a.children[idx];
            var bCell = b.children[idx];
            var aVal = aCell.getAttribute('data-sort') || aCell.textContent.trim();
            var bVal = bCell.getAttribute('data-sort') || bCell.textContent.trim();

            // Numeric comparison
            var aNum = parseFloat(aVal);
            var bNum = parseFloat(bVal);
            if (!isNaN(aNum) && !isNaN(bNum)) {
              return asc ? aNum - bNum : bNum - aNum;
            }

            // String comparison
            return asc ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
          });

          rows.forEach(function(row) { tbody.appendChild(row); });
        });
      });
    }
  };

  /* ── Global Search ── */
  const Search = {
    init: function() {
      var input = document.getElementById('globalSearch');
      if (!input) return;

      input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          var q = this.value.trim();
          if (q.length > 0) {
            Toast.show('Búsqueda', 'Buscando: "' + q + '"...');
          }
        }
      });
    }
  };

  /* ── Initialize ── */
  document.addEventListener('DOMContentLoaded', function() {
    Toast.init();
    Modal.init();
    TableSort.init();
    Search.init();

    // Show welcome toast on dashboard
    var path = new URLSearchParams(window.location.search).get('r') || '/dashboard';
    if (path === '/dashboard' || path === '/') {
      setTimeout(function() {
        Toast.show('Bienvenido', 'Sesión iniciada correctamente');
      }, 600);
    }
  });

  // Expose for external use
  window.SP = { Toast: Toast, Modal: Modal };
})();
