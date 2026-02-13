<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Estimation d'un bien immobilier</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; }
    .container { max-width: 800px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    h1 { text-align: center; color: #c40000; }
    label { display: block; margin-top: 12px; font-weight: bold; }
    input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border-radius: 8px; border: 1px solid #ccc; }
    .btn { margin-top: 20px; width: 100%; padding: 14px; background: #c40000; color: #fff; border: none; border-radius: 10px; cursor: pointer; }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container">
    <h1>Demande d’estimation immobilière</h1>

    <form action="traitement_estimation.php" method="POST">

      <label>Nom et prénom</label>
      <input type="text" name="nom" required>

      <label>Téléphone</label>
      <input type="text" name="telephone" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Ville</label>
      <input type="text" name="ville" required>

      <label>Type de bien</label>
      <select name="type_bien" required>
        <option value="">Sélectionner</option>
        <option>Appartement</option>
        <option>Maison</option>
        <option>Villa</option>
        <option>Terrain</option>
        <option>Local commercial</option>
      </select>

      <label>Prix souhaité (DT)</label>
      <input type="number" name="prix">

      <button class="btn" type="submit">Envoyer la demande</button>

    </form>
    
    <div style="margin-top: 20px; text-align: center;">
        <a href="admin_estimations.php">Voir les demandes (Admin)</a>
    </div>
  </div>
</body>
</html>