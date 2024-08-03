<form action="/upload_csv" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".csv">
    <button type="submit">Import CSV</button>
</form>