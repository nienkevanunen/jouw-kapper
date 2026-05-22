(function () {
  'use strict';

  var config = {
    owner: 'nienkevanunen',
    repo: 'jouw-kapper',
    branch: 'github-pages-static',
    files: [
      { path: 'data/site.json', label: 'Contact, adres en links' },
      { path: 'data/page-text.json', label: 'Teksten' },
      { path: 'data/opening-hours.json', label: 'Openingstijden' },
      { path: 'data/prices.json', label: 'Prijzen' },
      { path: 'data/promotions.json', label: 'Acties' },
      { path: 'data/gallery.json', label: 'Portfolio' }
    ]
  };

  var tokenInput = document.getElementById('token');
  var rememberInput = document.getElementById('remember-token');
  var forgetButton = document.getElementById('forget-token');
  var fileSelect = document.getElementById('content-file');
  var branchInput = document.getElementById('branch');
  var editor = document.getElementById('editor');
  var status = document.getElementById('status');
  var loadButton = document.getElementById('load-file');
  var saveButton = document.getElementById('save-file');
  var currentSha = '';

  config.files.forEach(function (file) {
    var option = document.createElement('option');
    option.value = file.path;
    option.textContent = file.label + ' (' + file.path + ')';
    fileSelect.appendChild(option);
  });

  var savedToken = localStorage.getItem('jouwKapperGithubToken');
  if (savedToken) {
    tokenInput.value = savedToken;
    rememberInput.checked = true;
  }

  branchInput.value = config.branch;

  function setStatus(message, type) {
    status.textContent = message;
    status.className = 'status' + (type ? ' ' + type : '');
  }

  function getToken() {
    return tokenInput.value.trim();
  }

  function getBranch() {
    return branchInput.value.trim() || config.branch;
  }

  function githubUrl(path) {
    return 'https://api.github.com/repos/' + config.owner + '/' + config.repo + '/contents/' + path;
  }

  function decodeBase64(value) {
    return decodeURIComponent(escape(atob(value.replace(/\n/g, ''))));
  }

  function encodeBase64(value) {
    return btoa(unescape(encodeURIComponent(value)));
  }

  function headers() {
    var token = getToken();
    if (!token) {
      throw new Error('Vul eerst een GitHub token in.');
    }

    return {
      Accept: 'application/vnd.github+json',
      Authorization: 'Bearer ' + token,
      'X-GitHub-Api-Version': '2022-11-28'
    };
  }

  function rememberTokenIfNeeded() {
    if (rememberInput.checked) {
      localStorage.setItem('jouwKapperGithubToken', getToken());
    } else {
      localStorage.removeItem('jouwKapperGithubToken');
    }
  }

  function selectedFile() {
    return fileSelect.value;
  }

  function loadFile() {
    var path = selectedFile();
    setStatus('Laden...', '');

    return fetch(githubUrl(path) + '?ref=' + encodeURIComponent(getBranch()), {
      headers: headers()
    })
      .then(function (response) {
        if (!response.ok) {
          throw new Error('GitHub kon het bestand niet laden (' + response.status + ').');
        }
        return response.json();
      })
      .then(function (data) {
        currentSha = data.sha;
        editor.value = JSON.stringify(JSON.parse(decodeBase64(data.content)), null, 2) + '\n';
        rememberTokenIfNeeded();
        setStatus('Geladen: ' + path, 'success');
      })
      .catch(function (error) {
        setStatus(error.message, 'error');
      });
  }

  function saveFile() {
    var path = selectedFile();
    var parsed;

    try {
      parsed = JSON.parse(editor.value);
    } catch (error) {
      setStatus('Dit is geen geldige JSON: ' + error.message, 'error');
      return;
    }

    if (!currentSha) {
      setStatus('Laad het bestand eerst voordat je opslaat.', 'error');
      return;
    }

    var formatted = JSON.stringify(parsed, null, 2) + '\n';
    setStatus('Opslaan...', '');

    fetch(githubUrl(path), {
      method: 'PUT',
      headers: Object.assign(headers(), {
        'Content-Type': 'application/json'
      }),
      body: JSON.stringify({
        message: 'Update website content: ' + path,
        content: encodeBase64(formatted),
        sha: currentSha,
        branch: getBranch()
      })
    })
      .then(function (response) {
        if (!response.ok) {
          return response.json().then(function (data) {
            throw new Error(data.message || 'GitHub kon het bestand niet opslaan.');
          });
        }
        return response.json();
      })
      .then(function (data) {
        currentSha = data.content.sha;
        editor.value = formatted;
        rememberTokenIfNeeded();
        setStatus('Opgeslagen. GitHub Pages publiceert de wijziging nu opnieuw.', 'success');
      })
      .catch(function (error) {
        setStatus(error.message, 'error');
      });
  }

  fileSelect.addEventListener('change', function () {
    currentSha = '';
    editor.value = '';
    setStatus('Klik op Laden om dit bestand te openen.', '');
  });

  loadButton.addEventListener('click', loadFile);
  saveButton.addEventListener('click', saveFile);
  forgetButton.addEventListener('click', function () {
    localStorage.removeItem('jouwKapperGithubToken');
    tokenInput.value = '';
    rememberInput.checked = false;
    setStatus('Token verwijderd uit deze browser.', 'success');
  });
})();
