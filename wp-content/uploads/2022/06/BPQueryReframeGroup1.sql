select * FROM BPS_Patients
WHERE StatusText = 'Active'
AND  DOB < DateAdd(Year, -50, GetDate())
--Not currently taking these drugs
AND NOT InternalID IN (SELECT InternalID FROM [BPSPATIENTS].[dbo].CURRENTRX 
										 WHERE (DRUGNAME LIKE 'ACLASTA%'
										 OR DRUGNAME LIKE 'ACTONEL%'
										 OR DRUGNAME LIKE'EVISTA%'
										 OR DRUGNAME LIKE 'FORTEO%'
										 OR DRUGNAME LIKE  'FOSAMAX%'
										 OR DRUGNAME LIKE 'PROLIA%'
										 )
										 AND RecordStatus = 1 AND RxStatus = 1
										 )
--Not currently taking drugs with these ingredients
AND NOT InternalID IN (SELECT InternalID FROM [BPSPATIENTS].[dbo].CURRENTRX WHERE ProductID IN
							(
							SELECT prod_ing.PRODUCTID FROM [BPSDrugs].[dbo].[PRODUCT_INGREDIENT] prod_ing
								INNER JOIN [BPSDrugs].[dbo].[INGREDIENTS] ing on prod_ing.INGREDIENTID = ing.INGREDIENTID
								WHERE (INGREDIENTNAME LIKE 'ALENDRONATE%'
								    OR INGREDIENTNAME LIKE 'ALENDRONIC ACID%'
									OR INGREDIENTNAME LIKE 'DENOSUMAB%'
									OR INGREDIENTNAME LIKE 'RALOXIFENE%'
									OR INGREDIENTNAME LIKE 'RISEDRONATE%'
									OR INGREDIENTNAME LIKE 'TERIPARATIDE%'
									OR INGREDIENTNAME LIKE 'ZOLEDRONATE%'
									OR INGREDIENTNAME LIKE 'ZOLEDRONIC ACID%'
									)
								AND RecordStatus = 1 AND RxStatus = 1
															
					)
)
--But have been prescribed these drugs in the past
AND 
( InternalID IN (SELECT ScriptItems.InternalID FROM [BPSPATIENTS].[dbo].ScriptItems 
												 INNER JOIN [BPSPATIENTS].[dbo].Prescriptions ON ScriptItems.ScriptID = Prescriptions.ScriptID
												 WHERE (PRODUCTNAME LIKE 'ACLASTA%'
													 OR PRODUCTNAME LIKE 'ACTONEL%'
													 OR PRODUCTNAME LIKE'EVISTA%'
													 OR PRODUCTNAME LIKE 'FORTEO%'
													 OR PRODUCTNAME LIKE  'FOSAMAX%'
													 OR PRODUCTNAME LIKE 'PROLIA%'
													 )
													 AND ScriptDate >= DateAdd(Year, -5, GetDate()) AND ScriptDate <= GetDate() AND Prescriptions.RecordStatus = 1
										 )
--But have been prescribed drugs with these ingredients in in the past
OR InternalID IN (SELECT ScriptItems.InternalID FROM [BPSPATIENTS].[dbo].ScriptItems 
												 INNER JOIN [BPSPATIENTS].[dbo].Prescriptions ON ScriptItems.ScriptID = Prescriptions.ScriptID
												 WHERE PRODUCTID IN
												 (
													SELECT prod_ing.PRODUCTID FROM [BPSDrugs].[dbo].[PRODUCT_INGREDIENT] prod_ing
													INNER JOIN [BPSDrugs].[dbo].[INGREDIENTS] ing on prod_ing.INGREDIENTID = ing.INGREDIENTID
													WHERE (INGREDIENTNAME LIKE 'ALENDRONATE%'
													    OR INGREDIENTNAME LIKE 'ALENDRONIC ACID%'
														OR INGREDIENTNAME LIKE 'DENOSUMAB%'
														OR INGREDIENTNAME LIKE 'RALOXIFENE%'
														OR INGREDIENTNAME LIKE 'RISEDRONATE%'
														OR INGREDIENTNAME LIKE 'TERIPARATIDE%'
														OR INGREDIENTNAME LIKE 'ZOLEDRONATE%'
														OR INGREDIENTNAME LIKE 'ZOLEDRONIC ACID%'
														)
												 )
												 AND ScriptDate >= DateAdd(Year, -5, GetDate()) AND ScriptDate <= GetDate() AND Prescriptions.RecordStatus = 1
									  
						 )
)
--Patients that have been seen in the last 2 years only.
AND InternalID IN (SELECT InternalID FROM [BPSPATIENTS].[dbo].Visits WHERE VisitDate >= DateAdd(Year, -2, GetDate()) AND VisitDate <= GetDate() AND RecordStatus = 1)
order by Surname,Firstname